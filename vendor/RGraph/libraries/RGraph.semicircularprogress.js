// Version: 2020-05-31
//
    // o--------------------------------------------------------------------------------o
    // | This file is part of the RGraph package - you can learn more at:               |
    // |                                                                                |
    // |                         https://www.rgraph.net                                 |
    // |                                                                                |
    // | RGraph is licensed under the Open Source MIT license. That means that it's     |
    // | totally free to use and there are no restrictions on what you can do with it!  |
    // o--------------------------------------------------------------------------------o

    RGraph = window.RGraph || {isrgraph:true,isRGraph:true,rgraph:true};




    //
    // The progress bar constructor
    //
    RGraph.SemiCircularProgress = function (conf)
    {
        this.id                = conf.id;
        this.canvas            = document.getElementById(this.id);
        this.context           = this.canvas.getContext('2d');
        this.canvas.__object__ = this;

        this.min               = RGraph.stringsToNumbers(conf.min);
        this.max               = RGraph.stringsToNumbers(conf.max);
        this.value             = RGraph.stringsToNumbers(conf.value);
        this.type              = 'semicircularprogress';
        this.coords            = [];
        this.isRGraph          = true;
        this.isrgraph          = true;
        this.rgraph            = true;
        this.currentValue      = null;
        this.uid               = RGraph.createUID();
        this.canvas.uid        = this.canvas.uid ? this.canvas.uid : RGraph.createUID();
        this.colorsParsed      = false;
        this.coordsText        = [];
        this.original_colors   = [];
        this.firstDraw         = true; // After the first draw this will be false


        this.properties =
        {
            backgroundColor:            'rgba(0,0,0,0)',

            colors:                     ['#0c0'],

            linewidth:                  2,

            colorsStroke:               '#666',

            marginLeft:                 35,
            marginRight:                35,
            marginTop:                  35,
            marginBottom:               35,

            radius:                     null,
            centerx:                    null,
            centery:                    null,

            width:                      null,

            anglesStart:                Math.PI,
            anglesEnd:                  (2 * Math.PI),

            scaleDecimals:              0,
            scalePoint:                 '.',
            scaleThousand:              ',',
            scaleFormatter:             null,
            scaleRound:                 false,
            scaleUnitsPre:              '',
            scaleUnitsPost:             '',

            shadow:                     false,
            shadowColor:                'rgba(220,220,220,1)',
            shadowBlur:                 2,
            shadowOffsetx:              2,
            shadowOffsety:              2,

            labelsCenter:               true,
            labelsCenterFade:           false,
            labelsCenterSize:           40,
            labelsCenterColor:          null,
            labelsCenterBold:           null,
            labelsCenterItalic:         null,
            labelsCenterFont:           null,
            labelsCenterValign:         'bottom',
            labelsMinColor:             null,
            labelsMinFont:              null,
            labelsMinBold:              null,
            labelsMinSize:              null,
            labelsMinItalic:            null,
            labelsMinOffsetAngle:       0,
            labelsMinOffsetx:           0,
            labelsMinOffsety:           5,
            labelsMaxColor:             null,
            labelsMaxFont:              null,
            labelsMaxBold:              null,
            labelsMaxSize:              null,
            labelsMaxItalic:            null,
            labelsMaxOffsetAngle:       0,
            labelsMaxOffsetx:           0,
            labelsMaxOffsety:           5,
            
            title:                      '',
            titleBold:                  null,
            titleItalic:                null,
            titleFont:                  null,
            titleSize:                  null,
            titleColor:                 null,
            
            textSize:                   12,
            textColor:                  'black',
            textFont:                   'Arial, Verdana, sans-serif',
            textBold:                   false,
            textItalic:                 false,
            textAccessible:             true,
            textAccessibleOverflow:     'visible',
            textAccessiblePointerevents:false,

            contextmenu:                null,

            tooltips:                   null,
            tooltipsEffect:             'fade',
            tooltipsCssClass:           'RGraph_tooltip',
            tooltipsCss:                null,
            tooltipsHighlight:          true,
            tooltipsEvent:              'onclick',
            tooltipsCoordsPage:         true,
            tooltipsFormattedThousand:  ',',
            tooltipsFormattedPoint:     '.',
            tooltipsFormattedDecimals:  0,
            tooltipsFormattedUnitsPre:  '',
            tooltipsFormattedUnitsPost: '',
            tooltipsFormattedKeyColors: null,
            tooltipsFormattedKeyColorsShape: 'square',
            tooltipsFormattedKeyLabels: [],

            highlightStroke:            'rgba(0,0,0,0)',
            highlightFill:              'rgba(255,255,255,0.7)',

            annotatable:                false,
            annotatebleColor:           'black',
            annotatebleLinewidth:       1,

            resizable:                  false,
            resizableHandleAdjust:      [0,0],
            resizableHandleBackground:  null,

            adjustable:                 false,

            clearto:                    'rgba(0,0,0,0)'
        }

        // Check for support
        if (!this.canvas) {
            alert('[SEMICIRCULARPROGRESS] No canvas support');
            return;
        }




        //
        // Translate half a pixel for antialiasing purposes - but only if it hasn't beeen
        // done already
        //
        if (!this.canvas.__rgraph_aa_translated__) {
            this.context.translate(0.5,0.5);
            
            this.canvas.__rgraph_aa_translated__ = true;
        }




        // Easy access to  properties and the path function
        var prop  = this.properties;
        this.path = RGraph.pathObjectFunction;
        
        
        
        //
        // "Decorate" the object with the generic effects if the effects library has been included
        //
        if (RGraph.Effects && typeof RGraph.Effects.decorate === 'function') {
            RGraph.Effects.decorate(this);
        }
        
        
        
        // Add the responsive method. This method resides in the common file.
        this.responsive = RGraph.responsive;








        //
        // A generic setter
        // 
        // @param string name  The name of the property to set or it can also be an object containing
        //                     object style configuration
        //
        this.set = function (name)
        {
            var value = typeof arguments[1] === 'undefined' ? null : arguments[1];

            // the number of arguments is only one and it's an
            // object - parse it for configuration data and return.
            if (arguments.length === 1 && typeof arguments[0] === 'object') {
                for (i in arguments[0]) {
                    if (typeof i === 'string') {
                        this.set(i, arguments[0][i]);
                    }
                }

                return this;
            }

            prop[name] = value;

            return this;
        };








        //
        // A generic getter
        // 
        // @param string name  The name of the property to get
        //
        this.get = function (name)
        {
            return prop[name];
        };








        //
        // Draws the progress bar
        //
        this.draw = function ()
        {
            //
            // Fire the onbeforedraw event
            //
            RGraph.fireCustomEvent(this, 'onbeforedraw');



            //
            // Parse the colors. This allows for simple gradient syntax
            //
            if (!this.colorsParsed) {
    
                this.parseColors();
    
                
                // Don't want to do this again
                this.colorsParsed = true;
            }
    
            
            //
            // Set the current value
            //
            this.currentValue = this.value;



            //
            // Make the margins easy ro access
            //
            this.marginLeft   = prop.marginLeft;
            this.marginRight  = prop.marginRight;
            this.marginTop    = prop.marginTop;
            this.marginBottom = prop.marginBottom;
    
            // Figure out the width and height
            this.radius = Math.min(
                (this.canvas.width - prop.marginLeft - prop.marginRight) / 2,
                this.canvas.height - prop.marginTop - prop.marginBottom
            );
            this.centerx = ((this.canvas.width - this.marginLeft - this.marginRight) / 2) + this.marginLeft;
            this.centery = this.canvas.height - this.marginBottom;
            this.width   = this.radius / 3;
             
            // User specified centerx/y/radius
            if (typeof prop.radius  === 'number') this.radius = prop.radius;
            if (typeof prop.centerx === 'number') this.centerx = prop.centerx;
            if (typeof prop.centery === 'number') this.centery = prop.centery;
            if (typeof prop.width   === 'number') this.width   = prop.width;

            this.coords = [];



            //
            // Stop this growing uncontrollably
            //
            this.coordsText = [];




    
            //
            // Draw the meter
            //
            this.drawMeter();
            this.drawLabels();
    
    
    
            //
            // Setup the context menu if required
            //
            if (prop.contextmenu) {
                RGraph.showContext(this);
            }
    
    
            //
            // This installs the event listeners
            //
            RGraph.installEventListeners(this);

    
    
            
            //
            // This function enables resizing
            //
            if (prop.resizable) {
                RGraph.allowResizing(this);
            }
            
            //
            // Instead of using RGraph.common.adjusting.js, handle them here
            //
            this.allowAdjusting();


            //
            // Fire the onfirstdraw event
            //
            if (this.firstDraw) {
                this.firstDraw = false;
                RGraph.fireCustomEvent(this, 'onfirstdraw');
                this.firstDrawFunc();
            }




            //
            // Fire the RGraph draw event
            //
            RGraph.fireCustomEvent(this, 'ondraw');
            
            return this;
        };








        //
        // Draw the bar itself
        //
        this.drawMeter = function ()
        {
            //
            // The start/end angles
            //
            var start = prop.anglesStart,
                end   = prop.anglesEnd;

            //
            // Calculate a scale (though only two labels are shown)
            //

            this.scale2 = RGraph.getScale({object: this, options: {
                'scale.max':          this.max,
                'scale.strict':       true,
                'scale.min':          this.min,
                'scale.thousand':     prop.scaleThousand,
                'scale.point':        prop.scalePoint,
                'scale.decimals':     prop.scaleDecimals,
                'scale.labels.count': 5,
                'scale.units.pre':    prop.scaleUnitsPre,
                'scale.units.post':   prop.scaleUnitsPost
            }});

            // Draw the backgrundColor
            if (prop.backgroundColor !== 'rgba(0,0,0,0)') {
                this.path(
                    'fs % fr % % % %',
                    prop.backgroundColor,
                    0,0,this.canvas.width, this.canvas.height
                );
            }


            // Draw the main semi-circle background and then lighten it by filling it again
            // in semi-transparent white
            this.path(
                'lw % b a % % % % % false a % % % % % true c s % f % sx % sy % sc % sb % f % sx 0 sy 0 sb 0 sc rgba(0,0,0,0) lw 1',
                prop.linewidth,
                this.centerx, this.centery, this.radius, start, end,
                this.centerx, this.centery, this.radius - this.width, end, start,
                prop.colorsStroke,
                typeof prop.colors[1] !== 'undefined' ? prop.colors[1] : prop.colors[0],
                prop.shadowOffsetx, prop.shadowOffsety, prop.shadow ? prop.shadowColor : 'rgba(0,0,0,0)', prop.shadowBlur,
                typeof prop.colors[1] !== 'undefined' ? 'rgba(0,0,0,0)' : 'rgba(255,255,255,0.85)'
            );

            var angle = start + ((end - start) * ((this.value - this.scale2.min) / (this.max - this.scale2.min)));

            // Draw the meter
            this.path(
                'b a % % % % % false a % % % % % true c f %',
                this.centerx, this.centery, this.radius, start, angle,
                this.centerx, this.centery, this.radius - this.width, start + ((end - start) * ((this.value - this.scale2.min) / (this.max - this.scale2.min))), start,
                prop.colors[0]
            );

            this.coords = [[
                this.centerx,
                this.centery,
                this.radius,
                start,
                end,
                this.width,
                angle
            ]];
        };








        //
        // The function that draws the labels
        //
        this.drawLabels = function ()
        {
            var min = RGraph.numberFormat({
                object:    this,
                number:    this.scale2.min.toFixed(typeof prop.labelsMinDecimals === 'number'? prop.labelsMinDecimals : prop.scaleDecimals),
                unitspre:  typeof prop.labelsMinUnitsPre  === 'string' ? prop.labelsMinUnitsPre  : prop.scaleUnitsPre,
                unitspost: typeof prop.labelsMinUnitsPost === 'string' ? prop.labelsMinUnitsPost : prop.scaleUnitsPost,
                point:     typeof prop.labelsMinPoint      === 'string' ? prop.labelsMinPoint      : prop.scalePoint,
                thousand:  typeof prop.labelsMinThousand   === 'string' ? prop.labelsMinThousand   : prop.scaleThousand
            });

            var max = RGraph.numberFormat({
                object:    this,
                number:    this.scale2.max.toFixed(typeof prop.labelsMaxDecimals === 'number'? prop.labelsMaxDecimals : prop.scaleDecimals),
                unitspre:  typeof prop.labelsMaxUnitsPre  === 'string' ? prop.labelsMaxUnitsPre  : prop.scaleUnitsPre,
                unitspost: typeof prop.labelsMaxUnitsPost === 'string' ? prop.labelsMaxUnitsPost : prop.scaleUnitsPost,
                point:     typeof prop.labelsMaxPoint      === 'string' ? prop.labelsMaxPoint      : prop.scalePoint,
                thousand:  typeof prop.labelsMaxThousand   === 'string' ? prop.labelsMaxThousand   : prop.scaleThousand
            });


            // Determine the horizontal and vertical alignment for the text
            if (prop.anglesStart === RGraph.PI) {
                var halign = 'center';
                var valign = 'top';
            
            } else if (prop.anglesStart <= RGraph.PI) {
                var halign = 'left';
                var valign = 'center';
            
            } else if (prop.anglesStart >= RGraph.PI) {
                var halign = 'right';
                var valign = 'center';
            }

            // Get the X/Y for the min label
            // cx, cy, angle, radius
            var xy = RGraph.getRadiusEndPoint(
                this.centerx,
                this.centery,
                prop.anglesStart + prop.labelsMinOffsetAngle,
                this.radius - (this.width / 2)
            );
            
            var textConf = RGraph.getTextConf({
                object: this,
                prefix: 'labelsMin'
            });


            // Draw the min label
            RGraph.text({
                
                object: this,
     
                font:   textConf.font,
                size:   textConf.size,
                color:  textConf.color,
                bold:   textConf.bold,
                italic: textConf.italic,

                x: xy[0] + prop.labelsMinOffsetx,
                y: xy[1] + prop.labelsMinOffsety,
                valign: valign,
                halign: halign,
                text: min
            });










            // Determine the horizontal and vertical alignment for the text
            if (prop.anglesEnd === RGraph.TWOPI) {
                var halign = 'center';
                var valign = 'top';
            
            } else if (prop.anglesEnd >= RGraph.TWOPI) {
                var halign = 'right';
                var valign = 'center';
            
            } else if (prop.anglesEnd <= RGraph.TWOPI) {
                var halign = 'left';
                var valign = 'center';
            }
            
            // Get the X/Y for the max label
            // cx, cy, angle, radius
            var xy = RGraph.getRadiusEndPoint(
                this.centerx,
                this.centery,
                prop.anglesEnd + prop.labelsMaxOffsetAngle,
                this.radius - (this.width / 2)
            );

            var textConf = RGraph.getTextConf({
                object: this,
                prefix: 'labelsMax'
            });

            // Draw the max label
            RGraph.text({
                
                object: this,
     
                font:   textConf.font,
                size:   textConf.size,
                color:  textConf.color,
                bold:   textConf.bold,
                italic: textConf.italic,

                x: xy[0] + prop.labelsMaxOffsetx,
                y: xy[1] + prop.labelsMaxOffsety,
                valign: valign,
                halign: halign,
                text: max
            });














            // Draw the big label in the center
            if (prop.labelsCenter) {

                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'labelsCenter'
                });

                var ret = RGraph.text({
                    
                    object: this,

                    font:   textConf.font,
                    size:   textConf.size,
                    color:  textConf.color,
                    bold:   textConf.bold,
                    italic: textConf.italic,

                    x:          this.centerx,
                    y:          this.centery,
                    valign:     prop.labelsCenterValign,
                    halign:     'center',
                    
                    text: RGraph.numberFormat({
                        object:    this,
                        number:    this.value.toFixed(typeof prop.labelsCenterDecimals === 'number' ? prop.labelsCenterDecimals : prop.scaleDecimals),
                        unitspre:  typeof prop.labelsCenterUnitsPre  === 'string' ? prop.labelsCenterUnitsPre  : prop.scaleUnitsPre,
                        unitspost: typeof prop.labelsCenterUnitsPost === 'string' ? prop.labelsCenterUnitsPost : prop.scaleUnitsPost,
                        point:     typeof prop.labelsCenterPoint      === 'string' ? prop.labelsCenterPoint      : prop.scalePoint,
                        thousand:  typeof prop.labelsCenterThousand   === 'string' ? prop.labelsCenterThousand   : prop.scaleThousand
                    })
                });
                
                // Allows the center label to fade in
                if (prop.labelsCenterFade && ret.node) {
                    ret.node.style.opacity = 0;
    
                    var delay = 25,
                        incr  = 0.1;
    
                    for (var i=0; i<10; ++i) {
                        (function (index)
                        {
                            setTimeout(function  ()
                            {
                                ret.node.style.opacity = incr * index;
                            }, delay * (index + 1));
                        })(i);
                    }
                }
            }
            
            // Draw the title
            RGraph.drawTitle(
                this,
                prop.title,
                this.marginTop,
                null,
                prop.titleSize
            );
        };








        //
        // Returns the focused bar
        // 
        // @param event e The event object
        //
        this.getShape = function (e)
        {
            var mouseXY = RGraph.getMouseXY(e),
                mouseX  = mouseXY[0],
                mouseY  = mouseXY[1]

            // Draw the meter here but don't stroke or fill it
            // so that it can be tested with isPointInPath()
            this.path(
                'b a % % % % % false a % % % % % true',
                this.coords[0][0], this.coords[0][1], this.coords[0][2], this.coords[0][3], this.coords[0][6],
                this.coords[0][0], this.coords[0][1], this.coords[0][2] - this.coords[0][5], this.coords[0][6], this.coords[0][3]
            );



            if (this.context.isPointInPath(mouseX, mouseY)) {

                if (RGraph.parseTooltipText) {
                    var tooltip = RGraph.parseTooltipText(prop.tooltips, 0);
                }

                return {
                    object: this,
                         x: this.coords[0][0],
                         y: this.coords[0][1],
               radiusOuter: this.coords[0][2],
               radiusInner: this.coords[0][2] - this.width,
                     width: this.coords[0][5],
                angleStart: this.coords[0][3],
                  angleEnd: this.coords[0][6],
                     index: 0,
                   dataset: 0,
           sequentialIndex: 0,
                   tooltip: typeof tooltip === 'string' ? tooltip : null
                };
            }
        };








        //
        // This function returns the value that the mouse is positioned at, regardless of
        // the actual indicated value.
        // 
        // @param object e The event object
        //
        this.getValue = function (e)
        {
            var mouseXY = RGraph.getMouseXY(e),
                mouseX  = mouseXY[0],
                mouseY  = mouseXY[1],
                angle   = RGraph.getAngleByXY(
                    this.centerx,
                    this.centery,
                    mouseX,
                    mouseY
                );
                
                if (
                    angle &&
                    mouseX >= this.centerx
                    && mouseY > this.centery
                    ) {
                    
                    angle += RGraph.TWOPI;
                }

            if (angle < prop.anglesStart && mouseX > this.centerx) { angle = prop.anglesEnd; }
            if (angle < prop.anglesStart) { angle = prop.anglesStart; }

            var value = (((angle - prop.anglesStart) / (prop.anglesEnd - prop.anglesStart)) * (this.max - this.min)) + this.min;

            value = Math.max(value, this.min);
            value = Math.min(value, this.max);

            return value;
        };








        //
        // Each object type has its own Highlight() function which highlights the appropriate shape
        // 
        // @param object shape The shape to highlight
        //
        this.highlight = function (shape)
        {
            if (typeof prop.highlightStyle === 'function') {
                (prop.highlightStyle)(shape);
            } else {
                this.path(
                    'lw 5 b a % % % % % false a % % % % % true c f % s % lw 1',
                    shape.x, shape.y, shape.radiusOuter, shape.angleStart, shape.angleEnd,
                    shape.x, shape.y, shape.radiusInner, shape.angleEnd, shape.angleStart,
                    prop.highlightFill, prop.highlightStroke
                );
            }
        };








        //
        // The getObjectByXY() worker method. Don't call this call:
        // 
        // RGraph.ObjectRegistry.getObjectByXY(e)
        // 
        // @param object e The event object
        //
        this.getObjectByXY = function (e)
        {
            var mouseXY = RGraph.getMouseXY(e);

            // Draw a Path so that the coords can be tested
            // (but don't stroke/fill it
            this.path(
                'b a % % % % % false',
                this.centerx,this.centery,this.radius,prop.anglesStart,prop.anglesEnd
            );

            this.path(
                'a % % % % % true',
                this.centerx,this.centery,this.radius - this.width,prop.anglesEnd,prop.anglesStart
            );

            return this.context.isPointInPath(mouseXY[0], mouseXY[1]) ? this : null;
        };








        //
        // This function allows the progress to be  adjustable.
        // UPDATE: Not any more
        //
        this.allowAdjusting = function () {};








        //
        // This method handles the adjusting calculation for when the mouse is moved
        // 
        // @param object e The event object
        //
        this.adjusting_mousemove = function (e)
        {
            //
            // Handle adjusting for the HProgress
            //
            if (prop.adjustable && RGraph.Registry.get('adjusting') && RGraph.Registry.get('adjusting').uid == this.uid) {

                var value   = this.getValue(e);
                
                if (typeof value === 'number') {
    
                    // Fire the onadjust event
                    RGraph.fireCustomEvent(this, 'onadjust');

                    this.value = Number(value.toFixed(prop.scaleDecimals));
                    RGraph.redrawCanvas(this.canvas);
                }
            }
        };








        //
        // This function returns the appropriate angle (in radians) for the given
        // Y value
        // 
        // @param  int value The Y value you want the angle for
        // @returm int       The angle
        //
        this.getAngle = function (value)
        {
            if (value > this.max || value < this.min) {
                return null;
            }

            var angle = (value / this.max) * (prop.anglesEnd - prop.anglesStart)
                angle += prop.anglesStart;

            return angle;
        };








        //
        // This returns true/false as to whether the cursor is over the chart area.
        // The cursor does not necessarily have to be over the bar itself.
        //
        this.overChartArea = function  (e)
        {
            var mouseXY = RGraph.getMouseXY(e),
                mouseX  = mouseXY[0],
                mouseY  = mouseXY[1]

            // Draw the background to the Progress but don't stroke or fill it
            // so that it can be tested with isPointInPath()
            this.path(
                'b a % % % % % false a % % % % % true',
                this.coords[0][0], this.coords[0][1], this.coords[0][2], prop.anglesStart, prop.anglesEnd,
                this.coords[0][0], this.coords[0][1], this.coords[0][2] - this.coords[0][5], prop.anglesEnd, prop.anglesStart
            );

            return this.context.isPointInPath(mouseX, mouseY);
        };








        //
        // 
        //
        this.parseColors = function ()
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (this.original_colors.length === 0) {
                this.original_colors.backgroundColor = RGraph.arrayClone(prop.backgroundColor);
                this.original_colors.colors          = RGraph.arrayClone(prop.colors);
            }

            prop.colors[0] = this.parseSingleColorForGradient(prop.colors[0]);
            prop.colors[1] = this.parseSingleColorForGradient(prop.colors[1]);
            
            prop.colorsStroke      = this.parseSingleColorForGradient(prop.colorsStroke);
            prop.backgroundColor = this.parseSingleColorForGradient(prop.backgroundColor);
        };








        //
        // Use this function to reset the object to the post-constructor state. Eg reset colors if
        // need be etc
        //
        this.reset = function ()
        {
        };








        //
        // This parses a single color value
        //
        this.parseSingleColorForGradient = function (color)
        {
            if (!color || typeof color != 'string') {
                return color;
            }
    
            if (color.match(/^gradient\((.*)\)$/i)) {

                // Allow for JSON gradients
                if (color.match(/^gradient\(({.*})\)$/i)) {
                    return RGraph.parseJSONGradient({object: this, def: RegExp.$1});
                }

                var parts = RegExp.$1.split(':');
    
                // Create the gradient
                var grad = this.context.createLinearGradient(prop.marginLeft,0,this.canvas.width - prop.marginRight,0);
    
                var diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RGraph.trim(parts[0]));
    
                for (var j=1,len=parts.length; j<len; ++j) {
                    grad.addColorStop(j * diff, RGraph.trim(parts[j]));
                }
                
                return grad ? grad : color;
            }
    
            return grad ? grad : color;
        };








        //
        // Using a function to add events makes it easier to facilitate method chaining
        // 
        // @param string   type The type of even to add
        // @param function func 
        //
        this.on = function (type, func)
        {
            if (type.substr(0,2) !== 'on') {
                type = 'on' + type;
            }
            
            if (typeof this[type] !== 'function') {
                this[type] = func;
            } else {
                RGraph.addCustomEventListener(this, type, func);
            }
    
            return this;
        };








        //
        // Used in chaining. Runs a function there and then - not waiting for
        // the events to fire (eg the onbeforedraw event)
        // 
        // @param function func The function to execute
        //
        this.exec = function (func)
        {
            func(this);
            
            return this;
        };








        //
        // This function runs once only
        // (put at the end of the file (before any effects))
        //
        this.firstDrawFunc = function ()
        {
        };







        //
        // HProgress Grow effect (which is also the VPogress Grow effect)
        // 
        // @param object obj The chart object
        //
        this.grow = function ()
        {
            var obj           = this,
                initial_value = this.currentValue,
                opt           = arguments[0] || {},
                numFrames     = opt.frames || 30,
                frame         = 0,
                callback      = arguments[1] || function () {},
                diff          = this.value - Number(this.currentValue),
                increment     = diff  / numFrames;



            function iterator ()
            {
                frame++;
    
                if (frame <= numFrames) {
    
                    obj.value = initial_value + (increment * frame);
    
                    RGraph.clear(obj.canvas);
                    RGraph.redrawCanvas(obj.canvas);
                    
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    callback();
                }
            }
            
            iterator();
            
            return this;
        };








        //
        // A worker function that handles Bar chart specific tooltip substitutions
        //
        this.tooltipSubstitutions = function (opt)
        {
            return {
                  index: 0,
                dataset: 0,
        sequentialIndex: 0,
                  value: this.value,
                 values: [this.value]
            };
        };








        //
        // A worker function that returns the correct color/label/value
        //
        // @param object specific The indexes that are applicable
        // @param number index    The appropriate index
        //
        this.tooltipsFormattedCustom = function (specific, index)
        {
            var color = (prop.tooltipsFormattedKeyColors && prop.tooltipsFormattedKeyColors[0]) ? prop.tooltipsFormattedKeyColors[0] : prop.colors[0];
            var label = (prop.tooltipsFormattedKeyLabels && prop.tooltipsFormattedKeyLabels[0]) ? prop.tooltipsFormattedKeyLabels[0] : '';

            return {
                label: label,
                color: color
            };
        };








        //
        // The chart is now always registered
        //
        RGraph.register(this);








        //
        // This is the 'end' of the constructor so if the first argument
        // contains configuration data - handle that.
        //
        RGraph.parseObjectStyleConfig(this, conf.options);
    };