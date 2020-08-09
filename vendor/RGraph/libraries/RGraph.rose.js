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

    RGraph              = window.RGraph || {isrgraph:true,isRGraph:true,rgraph:true};
    RGraph.Effects      = RGraph.Effects || {};
    RGraph.Effects.Rose = RGraph.Effects.Rose || {};

    //
    // The rose chart constuctor
    //
    RGraph.Rose = function (conf)
    {
        this.id                = conf.id;
        this.canvas            = document.getElementById(this.id);
        this.context           = this.canvas.getContext ? this.canvas.getContext("2d") : null;
        this.data              = conf.data;
        this.canvas.__object__ = this;
        this.type              = 'rose';
        this.isRGraph          = true;
        this.isrgraph          = true;
        this.rgraph            = true;
        this.uid               = RGraph.createUID();
        this.canvas.uid        = this.canvas.uid ? this.canvas.uid : RGraph.createUID();
        this.colorsParsed      = false;
        this.coordsText        = [];
        this.original_colors   = [];
        this.firstDraw         = true; // After the first draw this will be false





        this.centerx = 0;
        this.centery = 0;
        this.radius  = 0;
        this.max     = 0;
        this.angles  = [];
        this.angles2 = [];

        this.properties =
        {
            axes:                           false,
            axesColor:                      'black',
            axesLinewidth:                  1,
            axesTickmarks:                  true,

            backgroundGrid:                 true,
            backgroundGridColor:            '#ccc',
            backgroundGridSize:             null,
            backgroundGridRadialsCount:     null,
            backgroundGridRadialsOffset:    0,
            backgroundGridCirclesCount:     5,
            // [TODO] Need linewidth setting
            
            centerx:                        null,
            centery:                        null,
            radius:                         null,
            
            anglesStart:                    0,            
            
            linewidth:                      1,
            
            colors:                         ['rgba(255,0,0,0.5)', 'rgba(255,255,0,0.5)', 'rgba(0,255,255,0.5)', 'rgb(0,255,0)', 'gray', 'blue', 'rgb(255,128,255)','green', 'pink', 'gray', 'aqua'],
            colorsSequential:               false,
            colorsAlpha:                    null,
            colorsStroke:                   'rgba(0,0,0,0)',
            
            margin:                        5,
            marginLeft:                    35,
            marginRight:                   35,
            marginTop:                     35,
            marginBottom:                  35,

            shadow:                        false,
            shadowColor:                   '#aaa',
            shadowOffsetx:                 0,
            shadowOffsety:                 0,
            shadowBlur:                    15,

            title:                         '',
            titleBackground:               null,
            titleHpos:                     null,
            titleVpos:                     null,
            titleBold:                     null,
            titleFont:                     null,
            titleSize:                     null,
            titleItalic:                   null,
            titleColor:                    null,
            titleX:                        null,
            titleY:                        null,
            titleHalign:                   null,
            titleValign:                   null,
            
            labels:                        null,
            labelsColor:                   null,
            labelsFont:                    null,
            labelsSize:                    null,
            labelsBold:                    null,
            labelsItalic:                  null,
            labelsPosition:                'center',
            labelsBoxed:                   false,
            labelsOffset:                  0,
            labelsAxes:                    'n',
            labelsAxesFont:                null,
            labelsAxesSize:                null,
            labelsAxesColor:               null,
            labelsAxesBold:                null,
            labelsAxesItalic:              null,
            labelsAxesCount:               5,
            
            textColor:                     'black',
            textFont:                      'Arial, Verdana, sans-serif',
            textSize:                      12,
            textBold:                      false,
            textItalic:                    false,
            textAccessible:                true,
            textAccessibleOverflow:       'visible',
            textAccessiblePointerevents:   false,

            key:                           null,
            keyBackground:                 'white',
            keyPosition:                   'graph',
            keyHalign:                     'right',
            keyShadow:                     false,
            keyShadowColor:                '#666',
            keyShadowBlur:                 3,
            keyShadowOffsetx:              2,
            keyShadowOffsety:              2,
            keyPositionGutterBoxed:        false,
            keyPositionX:                  null,
            keyPositionY:                  null,
            keyColorShape:                 'square',
            keyRounded:                    true,
            keyLinewidth:                  1,
            keyColors:                     null,
            keyInteractive:                false,
            keyInteractiveHighlightChartStroke: 'black',
            keyInteractiveHighlightChartFill: 'rgba(255,255,255,0.7)',
            keyInteractiveHighlightLabel:  'rgba(255,0,0,0.2)',
            keyLabelsColor:                null,
            keyLabelsFont:                 null,
            keyLabelsSize:                 null,
            keyLabelsBold:                 null,
            keyLabelsItalic:               null,
            keyLabelsOffsetx:              0,
            keyLabelsOffsety:              0,

            contextmenu:                   null,

            tooltips:                      null,
            tooltipsEvent:                 'onclick',
            tooltipsEffect:                'fade',
            tooltipsCssClass:              'RGraph_tooltip',
            tooltipsCss:                   null,
            tooltipsHighlight:             true,
            tooltipsFormattedThousand:     ',',
            tooltipsFormattedPoint:        '.',
            tooltipsFormattedDecimals:     0,
            tooltipsFormattedUnitsPre:     '',
            tooltipsFormattedUnitsPost:    '',
            tooltipsFormattedKeyColors:     null,
            tooltipsFormattedKeyColorsShape: 'square',
            tooltipsFormattedKeyLabels:     [],

            highlightStroke:               'rgba(0,0,0,0)',
            highlightFill:                 'rgba(255,255,255,0.7)',

            annotatable:                   false,
            annotatableColor:              'black',
            annotatableLinewidth:          1,

            resizable:                     false,
            resizableHandleAdjust:         [0,0],
            resizableHandleBackground:     null,

            adjustable:                    false,

            scaleMax:                      null,
            scaleMin:                      0,
            scaleDecimals:                 null,
            scalePoint:                    '.',
            scaleThousand:                 ',',
            scaleUnitsPre:                 '',
            scaleUnitsPost:                '',

            variant:                       'stacked',
            variantThreedDepth:            10,

            exploded:                      0,

            animationRoundrobinFactor:     1,
            animationRoundrobinRadius:     true,
            animationGrowMultiplier:       1,

            segmentHighlight:              false,
            segmentHighlightCount:         null,
            segmentHighlightFill:          'rgba(0,255,0,0.5)',
            segmentHighlightStroke:        'rgba(0,0,0,0)',

            clearto:                       'rgba(0,0,0,0)'
        }
        
        
        
        // Go through the data converting it to numbers
        for (var i=0; i<this.data.length; ++i) {
            if (typeof this.data[i] === 'string') {
                this.data[i] = parseFloat(this.data[i]);
            } else if (typeof this.data[i] === 'object') {
                for (var j=0; j<this.data[i].length; ++j) {
                    if (typeof this.data[i][j] === 'string') {
                        this.data[i][j] = parseFloat(this.data[i][j]);
                    }
                }
            }
        }




        //
        // Create the $ objects. In the case of non-equi-angular rose charts it actually creates too many $ objects,
        // but it doesn't matter.
        //
        var linear_data = RGraph.arrayLinearize(this.data);
        this.data_seq = linear_data; // Add .data_seq
        this.data_arr = linear_data; // Add .data_arr
        for (var i=0; i<linear_data.length; ++i) {
            this["$" + i] = {};
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
        // A simple setter
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
        // A simple getter
        // 
        // @param string name The name of the property to get
        //
        this.get = function (name)
        {
            return prop[name];
        };








        //
        // This method draws the rose chart
        //
        this.draw = function ()
        {
            //
            // Fire the onbeforedraw event
            //
            RGraph.fireCustomEvent(this, 'onbeforedraw');



            //
            // Make the margins easy ro access
            //
            this.marginLeft   = prop.marginLeft;
            this.marginRight  = prop.marginRight;
            this.marginTop    = prop.marginTop;
            this.marginBottom = prop.marginBottom;
    
            // Calculate the radius
            this.radius       = (Math.min(this.canvas.width - this.marginLeft - this.marginRight, this.canvas.height - this.marginTop - this.marginBottom) / 2);
            this.centerx      = ((this.canvas.width - this.marginLeft - this.marginRight) / 2) + this.marginLeft;
            this.centery      = ((this.canvas.height - this.marginTop - this.marginBottom) / 2) + this.marginTop;
            this.angles       = [];
            this.angles2      = [];
            this.total        = 0;
            this.startRadians = prop.anglesStart;
            this.coordsText   = [];

            //
            // Change the centerx marginally if the key is defined
            //
            if (prop.key && prop.key.length > 0 && prop.key.length >= 3) {
                this.centerx = this.centerx - this.marginRight + 5;
            }
    
    
    
            // User specified radius, centerx and centery
            if (typeof prop.centerx == 'number') this.centerx = prop.centerx;
            if (typeof prop.centery == 'number') this.centery = prop.centery;
            if (typeof prop.radius  == 'number') this.radius  = prop.radius;
    
            //
            // Parse the colors for gradients. Its down here so that the center X/Y can be used
            //
            if (!this.colorsParsed) {
    
                this.parseColors();
    
                // Don't want to do this again
                this.colorsParsed = true;
            }



        // 3D variant
        if (prop.variant.indexOf('3d') !== -1) {

            var scaleX = 1.5;

            this.context.setTransform(
                scaleX,
                0,
                0,
                1,
                (this.canvas.width * scaleX - this.canvas.width) * -0.5,
                0
            );
        }





            this.drawBackground();





            // If a 3D variant draw the depth
            if (prop.variant.indexOf('3d') !== -1) {
            
                // Setting the shadow here means that the first (the bottom Rose)
                // sill have a shadow but not upper iterations.
                RGraph.setShadow(this,'rgba(0,0,0,0.35)',0,15,25);
            
                for (var i=prop.variantThreedDepth; i>0; i-=1) {
            
                    this.centery -= 1;
            
                    this.drawRose({storeAngles: false});
            
                    //RGraph.setShadow(this,'rgba(0,0,0,0)',0,0,0);
                    RGraph.noShadow(this);
            
            
                    // Make the segments darker
                    for (var j=0,len=this.angles.length; j<len; j+=1) {
            
                        var a = this.angles[j];
            
                        this.path(
                            'b m % % a % % % % % false c f rgba(0,0,0,0.1) c f rgba(0,0,0,0.1)',
                            a[4], a[5],
                            a[4], a[5], a[3] + 1.5, a[0] - 0.01, a[1] + 0.01, false
                        );
                    }
                }
            }

            this.drawRose();
            this.drawLabels();

            //
            // Set the strokestyle to transparent because of a strange double stroke bug
            // 
            // DO NOT REMOVE
            //
            this.context.strokeStyle = 'rgba(0,0,0,0)'


            //
            // Setup the context menu if required
            //
            if (prop.contextmenu) {
                RGraph.showContext(this);
            }
    
            
            //
            // This function enables resizing
            //
            if (prop.resizable) {
                RGraph.allowResizing(this);
            }
    
            
            //
            // This function enables adjusting
            //
            if (prop.adjustable) {
                RGraph.allowAdjusting(this);
            }
    
    
            //
            // This installs the event listeners
            //
            RGraph.installEventListeners(this);





            //
            // Allow the segments to be highlighted
            //
            if (prop.segmentHighlight) {
                
                // Check to see if the dynamic library has been included
                if (!RGraph.allowSegmentHighlight) {
                    alert('[WARNING] The segment highlight function does not exist - have you included the dynamic library?');
                }

                RGraph.allowSegmentHighlight({
                    object: this,
                    count:  typeof prop.segmentHighlightCount === 'number' ? prop.segmentHighlightCount : this.data.length,
                    fill:   prop.segmentHighlightFill,
                    stroke: prop.segmentHighlightStroke
                });
            }



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
        // This method draws the rose charts background
        //
        this.drawBackground = function ()
        {
            this.context.lineWidth = 1;
    
    
            // Draw the background grey circles/spokes
            if (prop.backgroundGridCirclesCount) {
                
                if (typeof prop.backgroundGridCirclesCount == 'number') {
                    prop.backgroundGridCirclesSize = this.radius / prop.backgroundGridCirclesCount;
                }
        
                this.context.beginPath();
                    this.context.strokeStyle = prop.backgroundGridColor;
                    
                    // Radius must be greater than 0 for Opera to work
                    for (var i=prop.backgroundGridCirclesSize; i<=this.radius; i+=prop.backgroundGridCirclesSize) {
                        
                        // Hmmm... This is questionable
                        this.context.moveTo(this.centerx + i, this.centery);
            
                        // Radius must be greater than 0 for Opera to work
                        this.context.arc(
                            this.centerx,
                            this.centery,
                            i,
                            0,
                            RGraph.TWOPI,
                            false
                        );
                    }
                this.context.stroke();
    
    
    
    
    
    
                // Draw the background lines that go from the center outwards
                this.context.beginPath();
                if (typeof prop.backgroundGridRadialsCount !== 'number') {
                    prop.backgroundGridRadialsCount = this.data.length
                }
                
                if (prop.backgroundGridRadialsCount > 0) {

                    var num    = (360 / prop.backgroundGridRadialsCount);
                    var offset =  prop.backgroundGridRadialsOffset;

                    for (var i=0; i<=360; i+=num) {
                    
                        // Radius must be greater than 0 for Opera to work
                        this.context.arc(
                            this.centerx,
                            this.centery,
                            this.radius,
                            ((i / (180 / RGraph.PI)) - RGraph.HALFPI) + this.startRadians + offset,
                            (((i + 0.0001) / (180 / RGraph.PI)) - RGraph.HALFPI) + this.startRadians + offset,
                            false
                           );
    
                        this.context.lineTo(this.centerx, this.centery);
                    }
                    this.context.stroke();
                }
            }
    
    
    
            if (prop.axes) {
            
                this.context.beginPath();
                this.context.strokeStyle = prop.axesColor;
                this.context.lineWidth   = prop.axesLinewidth;

                // Draw the X axis
                this.context.moveTo(this.centerx - this.radius, Math.round(this.centery) );
                this.context.lineTo(this.centerx + this.radius, Math.round(this.centery) );
            
                if (prop.axesTickmarks) {
                    // Draw the X ends
                    this.context.moveTo(Math.round(this.centerx - this.radius), this.centery - 5);
                    this.context.lineTo(Math.round(this.centerx - this.radius), this.centery + 5);
                    this.context.moveTo(Math.round(this.centerx + this.radius), this.centery - 5);
                    this.context.lineTo(Math.round(this.centerx + this.radius), this.centery + 5);
                
                    // Draw the X check marks
                    for (var i=(this.centerx - this.radius); i<(this.centerx + this.radius); i+=(this.radius / 5)) {
                        this.context.moveTo(Math.round(i),  this.centery - 3);
                        this.context.lineTo(Math.round(i),  this.centery + 3.5);
                    }
                
                    // Draw the Y check marks
                    for (var i=(this.centery - this.radius); i<(this.centery + this.radius); i+=(this.radius / 5)) {
                        this.context.moveTo(this.centerx - 3, Math.round(i));
                        this.context.lineTo(this.centerx + 3, Math.round(i));
                    }
                }
            
                // Draw the Y axis
                this.context.moveTo(Math.round(this.centerx), this.centery - this.radius);
                this.context.lineTo(Math.round(this.centerx), this.centery + this.radius);
                
                if (prop.axesTickmarks) {
                    // Draw the Y ends
                    this.context.moveTo(this.centerx - 5, Math.round(this.centery - this.radius));
                    this.context.lineTo(this.centerx + 5, Math.round(this.centery - this.radius));
            
                    this.context.moveTo(this.centerx - 5, Math.round(this.centery + this.radius));
                    this.context.lineTo(this.centerx + 5, Math.round(this.centery + this.radius));
                }
                
                // Stroke it
                this.context.closePath();
                this.context.stroke();
            }
            
            this.path('b c');
        };








        //
        // This method draws the data on the graph
        //
        this.drawRose = function ()
        {
            var max    = 0,
                data   = this.data,
                margin = RGraph.toRadians(prop.margin),
                opt    = arguments[0] || {};

            this.context.lineWidth = prop.linewidth;
    
            // Work out the maximum value and the sum
            if (RGraph.isNull(prop.scaleMax)) {
    
                // Work out the max
                for (var i=0; i<data.length; ++i) {
                    if (typeof data[i] == 'number') {
                        max = Math.max(max, data[i]);
                    } else if (typeof data[i] == 'object' && prop.variant.indexOf('non-equi-angular') !== -1) {
                        max = Math.max(max, data[i][0]);
                    
                    // Fallback is stacked
                    } else {
                        max = Math.max(max, RGraph.arraySum(data[i]));
                    }
                }
    
                this.scale2 = RGraph.getScale({object: this, options: {
                    'scale.max':max,
                    'scale.min':0,
                    'scale.thousand':     prop.scaleThousand,
                    'scale.point':        prop.scalePoint,
                    'scale.decimals':     prop.scaleDecimals,
                    'scale.labels.count': prop.labelsAxesCount,
                    'scale.round':        prop.scaleRound,
                    'scale.units.pre':    prop.scaleUnitsPre,
                    'scale.units.post':   prop.scaleUnitsPost
                }});
                this.max = this.scale2.max;
    
            } else {
    
                var ymax = prop.scaleMax;
    
    
    
                this.scale2 = RGraph.getScale({object: this, options: {
                    'scale.max':          ymax,
                    'scale.strict':       true,
                    'scale.thousand':     prop.scaleThousand,
                    'scale.point':        prop.scalePoint,
                    'scale.decimals':     prop.scaleDecimals,
                    'scale.labels.count': prop.labelsAxesCount,
                    'scale.round':        prop.scaleRound,
                    'scale.units.pre':    prop.scaleUnitsPre,
                    'scale.units.post':   prop.scaleUnitsPost
                }});
                this.max = this.scale2.max
            }

            this.sum = RGraph.arraySum(data);
            
            // Move to the centre
            this.context.moveTo(this.centerx, this.centery);
        
            this.context.stroke(); // Stroke the background so it stays grey
        
            // Transparency
            if (prop.colorsAlpha) {
                this.context.globalAlpha = prop.colorsAlpha;
            }
            
            var sequentialIndex = 0;
    
            //
            // A non-equi-angular Rose chart
            //
            if (typeof prop.variant == 'string' && prop.variant.indexOf('non-equi-angular') !== -1) {

                var total=0;
                for (var i=0; i<data.length; ++i) {
                    total += data[i][1];
                }
                
                if (prop.shadow) {
                    RGraph.setShadow(
                        this,
                        prop.shadowColor,
                        prop.shadowOffsetx,
                        prop.shadowOffsety,
                        prop.shadowBlur
                    );
                }

                for (var i=0; i<this.data.length; ++i) {
                
                    var segmentRadians = ((this.data[i][1] / total) * RGraph.TWOPI);
                    var radius         = ((this.data[i][0] - prop.scaleMin) / (this.max - prop.scaleMin)) * this.radius;
                        radius = radius * prop.animationGrowMultiplier;
    
                    this.context.strokeStyle = prop.colorsStroke;
                    this.context.fillStyle   = prop.colors[0];
    
                    if (prop.colorsSequential) {
                        this.context.fillStyle = prop.colors[i];
                    }

                    this.context.beginPath(); // Begin the segment
    
                        var startAngle = (this.startRadians * prop.animationRoundrobinFactor) - RGraph.HALFPI + margin;
                        var endAngle   = ((this.startRadians + segmentRadians) * prop.animationRoundrobinFactor) - RGraph.HALFPI - margin;
    
                        var exploded  = this.getExploded(i, startAngle, endAngle, prop.exploded);
                        var explodedX = exploded[0];
                        var explodedY = exploded[1];
    
    
                        this.context.arc(
                            this.centerx + explodedX,
                            this.centery + explodedY,
                            prop.animationRoundrobinRadius ? radius * prop.animationRoundrobinFactor : radius,
                            startAngle,
                            endAngle,
                            0
                        );
                        this.context.lineTo(this.centerx + explodedX, this.centery + explodedY);
                    this.context.closePath(); // End the segment
                    
                    this.context.stroke();
                    this.context.fill();
                    
                    // Store the start and end angles

                    this.angles[i] = [
                        startAngle,
                        endAngle,
                        0,
                        prop.animationRoundrobinRadius ? radius * prop.animationRoundrobinFactor : radius,
                        this.centerx + explodedX,
                        this.centery + explodedY,
                        this.context.strokeStyle,
                        this.context.fillStyle
                    ];
                    
                    sequentialIndex++;
                    this.startRadians += segmentRadians;
                }
                
                // Turn the shadow off if it's enabled and redraw the chart
                if (prop.shadow) {
                    RGraph.noShadow(this);
                    this.redrawRose();
                }
                
                //
                // Now redraw the rose if the linewidth is larger than 2 so that the
                // fills appear under the strokes
                //
                if (prop.linewidth > 1) {
                    this.restrokeRose();
                }

            } else {
            
                var sequentialColorIndex = 0;
                
                if (prop.shadow) {
                    RGraph.setShadow(
                        this,
                        prop.shadowColor,
                        prop.shadowOffsetx,
                        prop.shadowOffsety,
                        prop.shadowBlur
                    );
                }

                //
                // Draw regular segments here
                //
                for (var i=0; i<this.data.length; ++i) {

                    var segmentRadians = (1 / this.data.length) * RGraph.TWOPI;

                    if (typeof this.data[i] == 'number') {
                        this.context.beginPath(); // Begin the segment
    
                            this.context.strokeStyle = prop.colorsStroke;
                            this.context.fillStyle = prop.colors[0];
            
                            //
                            // This allows sequential colors
                            //
                            if (prop.colorsSequential) {
                                this.context.fillStyle = prop.colors[i];
                            }

                            var radius = ((this.data[i] - prop.scaleMin) / (this.max - prop.scaleMin)) * this.radius;
                                radius = radius * prop.animationGrowMultiplier;
    
                            var startAngle = (this.startRadians * prop.animationRoundrobinFactor) - RGraph.HALFPI + margin;
                            var endAngle   = (this.startRadians * prop.animationRoundrobinFactor) + (segmentRadians * prop.animationRoundrobinFactor) - RGraph.HALFPI - margin;
    
                            var exploded  = this.getExploded(i, startAngle, endAngle, prop.exploded);
                            var explodedX = exploded[0];
                            var explodedY = exploded[1];
    
                            this.context.arc(
                                this.centerx + explodedX,
                                this.centery + explodedY,
                                prop.animationRoundrobinRadius ? radius * prop.animationRoundrobinFactor : radius,
                                startAngle,
                                endAngle,
                                0
                            );
                            this.context.lineTo(this.centerx + explodedX, this.centery + explodedY);
                        this.context.closePath(); // End the segment
                        this.context.fill();
                        this.context.stroke();

                        // This skirts a double-stroke bug
                        this.context.beginPath();

                        if (endAngle == 0) {
                            //endAngle = RGraph.TWOPI;
                        }

                        // Store the start and end angles
                        this.angles[i] = [
                            startAngle,
                            endAngle,
                            0,
                            radius * prop.animationRoundrobinFactor,
                            this.centerx + explodedX,
                            this.centery + explodedY,
                            this.context.strokeStyle,
                            this.context.fillStyle
                        ];
                        
                        sequentialIndex++;
    
                    //
                    // Draw a stacked segment
                    //
                    } else if (typeof this.data[i] == 'object') {

                        var margin = prop.margin / (180 / RGraph.PI);

                        
                        // Initialise the angles2 array
                        if (!this.angles2[i]) {
                            this.angles2[i] = [];
                        }
                        

                        for (var j=0; j<this.data[i].length; ++j) {

                            var startAngle = (this.startRadians * prop.animationRoundrobinFactor) - RGraph.HALFPI + margin;
                            var endAngle  = (this.startRadians * prop.animationRoundrobinFactor)+ (segmentRadians * prop.animationRoundrobinFactor) - RGraph.HALFPI - margin;
                        
                            var exploded  = this.getExploded(i, startAngle, endAngle, prop.exploded);
                            var explodedX = exploded[0];
                            var explodedY = exploded[1];
        
                            this.context.strokeStyle = prop.colorsStroke;
                            this.context.fillStyle   = prop.colors[j];
    
                            // This facilitates sequential color support
                            if (prop.colorsSequential) {
                                this.context.fillStyle = prop.colors[sequentialColorIndex++];
                            }
    
                            if (j == 0) {
                                this.context.beginPath(); // Begin the segment
                                    var startRadius = 0;
                                    var endRadius = ((this.data[i][j] - prop.scaleMin) / (this.max - prop.scaleMin)) * this.radius;
                                        endRadius = endRadius * prop.animationGrowMultiplier;
                        
                                    this.context.arc(this.centerx + explodedX,
                                           this.centery + explodedY,
                                           prop.animationRoundrobinRadius ? endRadius * prop.animationRoundrobinFactor : endRadius,
                                           startAngle,
                                           endAngle,
                                           0);
                                    this.context.lineTo(this.centerx + explodedX, this.centery + explodedY);
                                this.context.closePath(); // End the segment
                                this.context.stroke();
                                this.context.fill();

                                this.angles[sequentialIndex++] = [
                                    startAngle,
                                    endAngle,
                                    0,
                                    endRadius * prop.animationRoundrobinFactor,
                                    this.centerx + explodedX,
                                    this.centery + explodedY,
                                    this.context.strokeStyle,
                                    this.context.fillStyle
                                ];
        
                                this.angles2[i][j] = [
                                    startAngle,
                                    endAngle,
                                    0,
                                    endRadius * prop.animationRoundrobinFactor,
                                    this.centerx + explodedX,
                                    this.centery + explodedY,
                                    this.context.strokeStyle,
                                    this.context.fillStyle
                                ];
                            
                            } else {

                                this.context.beginPath(); // Begin the segment
                                    
                                    var startRadius = endRadius; // This comes from the prior iteration of this loop
                                    var endRadius = (((this.data[i][j] - prop.scaleMin) / (this.max - prop.scaleMin)) * this.radius) + startRadius;
                                        endRadius = endRadius * prop.animationGrowMultiplier;
                    
                                    this.context.arc(this.centerx + explodedX,
                                           this.centery + explodedY,
                                           startRadius  * prop.animationRoundrobinFactor,
                                           startAngle,
                                           endAngle,
                                           0);
                    
                                    this.context.arc(this.centerx + explodedX,
                                           this.centery + explodedY,
                                           endRadius  * prop.animationRoundrobinFactor,
                                           endAngle,
                                           startAngle,
                                           true);
                    
                                this.context.closePath(); // End the segment
                                this.context.stroke();
                                this.context.fill();
        

                                this.angles[sequentialIndex++] = [
                                    startAngle,
                                    endAngle,
                                    startRadius * prop.animationRoundrobinFactor,
                                    endRadius * prop.animationRoundrobinFactor,
                                    this.centerx + explodedX,
                                    this.centery + explodedY,
                                    this.context.strokeStyle,
                                    this.context.fillStyle
                                ];
        
                                this.angles2[i][j] = [
                                    startAngle,
                                    endAngle,
                                    startRadius * prop.animationRoundrobinFactor,
                                    endRadius * prop.animationRoundrobinFactor,
                                    this.centerx + explodedX,
                                    this.centery + explodedY,
                                    this.context.strokeStyle,
                                    this.context.fillStyle
                                ];
                            }
                        }
                    }
        
                    this.startRadians += segmentRadians;
                        
                }


                if (prop.shadow) {
                    RGraph.noShadow(this);
                }



                //
                // Now redraw the rose if the shadow is enabled so that
                // the rose appears over the shadow
                //
                if (prop.shadow) {
                    this.redrawRose();
                }


                //
                // Now redraw the rose if the linewidth is larger than 2 so that the
                // fills appear under the strokes
                //
                if (prop.linewidth > 1) {
                    this.restrokeRose();
                }


                //
                // Now redraw the rose if the shadow is enabled so that
                // the rose appears over the shadow
                //
                if (prop.shadow) {
                    this.redrawRose();
                }
            }
    
            // Turn off the transparency
            if (prop.colorsAlpha) {
                this.context.globalAlpha = 1;
            }
    
            // Draw the title if any has been set
            if (prop.title) {
                RGraph.drawTitle(
                    this,
                    prop.title,
                    (this.canvas.height / 2) - this.radius,
                    this.centerx,
                    prop.titleSize ? prop.titleSize : prop.textSize
                );
            }
        };








        //
        // This function redraws the stroke on the chart so that
        // the strokes appear above the fill
        //
        this.restrokeRose = function ()
        {
            var angles = this.angles;

            for (var i=0; i<angles.length; ++i) {
                this.path(
                    'b a % % % % % false a % % % % % true c s %',
                    angles[i][4], // x
                    angles[i][5], // y
                    angles[i][2], // radius
                    angles[i][0], // start angle
                    angles[i][1], // end angle

                    angles[i][4], // x
                    angles[i][5], // y
                    angles[i][3], // radius
                    angles[i][1], // end angle
                    angles[i][0], // start angle
                    angles[i][6] // strokestyle
                );
            }
        };








        //
        // This function redraws the rose if the shadow is enabled so the it
        // appears above the shadow
        //
        this.redrawRose = function ()
        {
            var angles = this.angles;

            for (var i=0; i<angles.length; ++i) {
                
                this.path(
                    'b a % % % % % false a % % % % % true c f % f % ',
                    angles[i][4], angles[i][5],angles[i][2],angles[i][0],angles[i][1],
                    angles[i][4],angles[i][5],angles[i][3],angles[i][1],angles[i][0],
                    angles[i][6],angles[i][7]
                );
            }
        };








        //
        // Unsuprisingly, draws the labels
        //
        this.drawLabels = function ()
        {
            this.context.lineWidth = 1;
            var key = prop.key;
    
            if (key && key.length) {
                RGraph.drawKey(this, key, prop.colors);
            }
            
            // Set the color to black
            this.context.fillStyle   = prop.textColor;
            this.context.strokeStyle = 'black';
            
            var radius      = this.radius,
                font        = prop.textFont,
                size        = prop.textSize,
                axes        = prop.labelsAxes.toLowerCase(),
                decimals    = prop.scaleDecimals,
                units_pre   = prop.scaleUnitsPre,
                units_post  = prop.scaleUnitsPost,
                centerx     = this.centerx,
                centery     = this.centery + (prop.variant.indexOf('3d') !== -1 ? prop.variantThreedDepth : 0);

            // Draw any circular labels
            if (typeof prop.labels == 'object' && prop.labels) {
                this.drawCircularLabels(this.context, prop.labels, font, size, radius + 10);
            }
    
    
            // Size can be specified seperately for the scale now
            if (typeof prop.textSize == 'number') {
                size = prop.textSize;
            }
    
    
            var color = 'rgba(255,255,255,0.8)';
    
            // The "North" axis labels
            if (axes.indexOf('n') > -1) {
            
                // The offset for the labels
                if (prop.backgroundAxes) {
                    var offset = -10;
                    var halign = 'right';
                } else {
                    var offset = 0;
                    var halign = 'center';
                }
                
                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'labelsAxes'
                });

                for (var i=0; i<prop.labelsAxesCount; ++i) {
                    RGraph.text({
                        
                   object: this,

                     font: textConf.font,
                     size: textConf.size,
                    color: textConf.color,
                     bold: textConf.bold,
                   italic: textConf.italic,

                        'x':centerx + offset,
                        'y':centery - (radius * ((i+1) / prop.labelsAxesCount)),
                        'text':this.scale2.labels[i],
                        'valign':'center',
                        'halign': halign,
                        'bounding':true,
                        'bounding.fill':color,
                        'bounding.stroke': 'rgba(0,0,0,0)',
                        'tag': 'scale'
                    });
                }
            }
    
            // The "South" axis labels
            if (axes.indexOf('s') > -1) {

                // The offset for the labels
                if (prop.backgroundAxes) {
                    var offset = -10;
                    var halign = 'right';
                } else {
                    var offset = 0;
                    var halign = 'center';
                }

                for (var i=0; i<prop.labelsAxesCount; ++i) {
                    RGraph.text({
                        
                   object: this,

                     font: textConf.font,
                     size: textConf.size,
                    color: textConf.color,
                     bold: textConf.bold,
                   italic: textConf.italic,

                        'x':centerx + offset,
                        'y':centery + (radius * ((i+1) / prop.labelsAxesCount)),
                        'text':this.scale2.labels[i],
                        'valign':'center',
                        'halign':halign,
                        'bounding':true,
                        'bounding.fill':color,
                        'bounding.stroke':'rgba(0,0,0,0)',
                        'tag': 'scale'
                    });
                }
            }
            
            // The "East" axis labels
            if (axes.indexOf('e') > -1) {
                for (var i=0; i<prop.labelsAxesCount; ++i) {
            
                    // The offset for the labels
                    if (prop.backgroundAxes) {
                        var offset = 10;
                        var valign = 'top';
                    } else {
                        var offset = 0;
                        var valign = 'center';
                    }

                    RGraph.text({
                        
                   object: this,

                     font: textConf.font,
                     size: textConf.size,
                    color: textConf.color,
                     bold: textConf.bold,
                   italic: textConf.italic,

                        'x':centerx + (radius * ((i+1) / prop.labelsAxesCount)),
                        'y':centery + offset,
                        'text':this.scale2.labels[i],
                        'valign':valign,
                        'halign':'center',
                        'bounding':true,
                        'bounding.fill':color,
                        'bounding.stroke':'rgba(0,0,0,0)',
                        'tag': 'scale'
                    });
                }
            }
    
            // The "West" axis labels
            if (axes.indexOf('w') > -1) {
                for (var i=0; i<prop.labelsAxesCount; ++i) {
            
                    // The offset for the labels
                    if (prop.backgroundAxes) {
                        var offset = 10;
                        var valign = 'top';
                    } else {
                        var offset = 0;
                        var valign = 'center';
                    }

                    RGraph.text({
                        
                   object: this,

                     font: textConf.font,
                     size: textConf.size,
                    color: textConf.color,
                     bold: textConf.bold,
                   italic: textConf.italic,

                        'x':centerx - (radius * ((i+1) / prop.labelsAxesCount)),
                        'y':centery + offset,
                        'text':this.scale2.labels[i],
                        'valign':valign,
                        'halign':'center',
                        'bounding':true,
                        'bounding.fill':color,
                        'bounding.stroke': 'rgba(0,0,0,0)',
                        'tag': 'scale'
                    });
                }
            }

            // Draw the minimum value
            if (RGraph.trim(axes).length > 0) {
                RGraph.text({
                    
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    'x':centerx,
                    'y':centery,
                    'text':typeof prop.scaleMin === 'number' ?
                               RGraph.numberFormat({
                                   object:    this,
                                   number:    Number(prop.scaleMin).toFixed(prop.scaleMin === 0 ? '0' : prop.scaleDecimals),
                                   unitspre:  units_pre,
                                   unitspost: units_post
                               }) : '0',
                    'valign':'center',
                    'halign':'center',
                    'bounding':true,
                    'bounding.fill':color,
                    'bounding.stroke':'rgba(0,0,0,0)',
                    'tag': 'scale'
                });
            }
        };








        //
        // Draws the circular labels that go around the charts
        // 
        // @param labels array The labels that go around the chart
        //
        this.drawCircularLabels = function (context, labels, font, size, radius)
        {
            var variant     = prop.variant,
                position    = prop.labelsPosition,
                radius      = radius + 5 + prop.labelsOffset,
                centerx     = this.centerx,
                centery     = this.centery + (prop.variant.indexOf('3d') !== -1 ? prop.variantThreedDepth : 0),
                labelsColor = prop.labelsColor || prop.textColor,
                angles      = this.angles


                
                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'labels'
                });
    
            for (var i=0; i<this.data.length; ++i) {
                
                if (typeof variant == 'string' && variant.indexOf('non-equi-angular') !== -1) {
                    var a = Number(angles[i][0]) + ((angles[i][1] - angles[i][0]) / 2);
                } else {
                    var a = (RGraph.TWOPI / this.data.length) * (i + 1) - (RGraph.TWOPI / (this.data.length * 2));
                        a = a - RGraph.HALFPI + (prop.labelsPosition == 'edge' ? ((RGraph.TWOPI / this.data.length) / 2) : 0);
                    
                    // MJLR bug fix 21/04/2020 - label positions ignored anglesStart property
                    a = a + prop.anglesStart;
                }
    
                var x = centerx + (Math.cos(a) * radius);
                var y = centery + (Math.sin(a) * radius);
    
                // Horizontal alignment
                if (x > centerx) {
                    halign = 'left';
                } else if (Math.round(x) == centerx) {
                    halign = 'center';
                } else {
                    halign = 'right';
                }
    
                RGraph.text({
                    
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:      x,
                    y:      y,
                    text:   String(labels[i] || ''),
                    halign: halign,
                    valign: 'center',
                    tag:    'labels'
                });
            }
        };








        //
        // This function is for use with circular graph types, eg the Pie or Rose. Pass it your event object
        // and it will pass you back the corresponding segment details as an array:
        // 
        // [x, y, r, startAngle, endAngle]
        // 
        // Angles are measured in degrees, and are measured from the "east" axis (just like the canvas).
        // 
        // @param object e   Your event object
        // @param object Options (OPTIONAL):
        //                radius - whether to take into account
        //                         the radius of the segment
        //
        this.getShape = function (e)
        {
            var angles  = this.angles;
            var ret     = [];
            var opt     = arguments[1] ? arguments[1] : {radius: true};
    
            //
            // Go through all of the angles checking each one
            //
            for (var i=0; i<angles.length ; ++i) {
    
                var angleStart  = angles[i][0];
                var angleEnd    = angles[i][1];
                var radiusStart = opt.radius === false ? 0 : angles[i][2];
                var radiusEnd   = opt.radius === false ? this.radius : angles[i][3];
                var centerX     = angles[i][4];
                var centerY     = angles[i][5];// - (prop.variant.indexOf('3d') !== -1 ? prop.variantThreedDepth : 0);
                var mouseXY     = RGraph.getMouseXY(e);
                var mouseX      = mouseXY[0] - centerX;
                var mouseY      = mouseXY[1] - centerY;
    
                // New click testing (the 0.01 is there because Opera doesn't like 0 as the radius)
                this.path(
                    'b a % % % % % % a % % % % % % c',
                    centerX, centerY, radiusStart ? radiusStart : 0.01, angleStart, angleEnd, false,
                    centerX, centerY, radiusEnd, angleEnd, angleStart, true
                );
    
                // No stroke() or fill()
    
    
                if (this.context.isPointInPath(mouseXY[0], mouseXY[1])) {

                    angles[i][6] = i;
                    
                    if (RGraph.parseTooltipText) {
                        var tooltip = RGraph.parseTooltipText(prop.tooltips, angles[i][6]);
                    }
                    
                    var indexes = RGraph.sequentialIndexToGrouped(i, this.data);
    
                    // Add the textual keys which are used in the return value
                    //
                    angles[i].object          = this;
                    angles[i].x               = angles[i][4];
                    angles[i].y               = angles[i][5];
                    angles[i]['angle.start']  = angles[i][0];
                    angles[i]['angle.end']    = angles[i][1];
                    angles[i]['radius.start'] = angles[i][2];
                    angles[i]['radius.end']   = angles[i][3];
                    angles[i].index           = indexes[1];
                    angles[i].dataset         = indexes[0];
                    angles[i].sequentialIndex = angles[i][6];
                    angles[i].tooltip         = tooltip ? tooltip : null;
                    angles[i].label           = (
                                                    typeof prop.labels === 'object'
                                                 && !RGraph.isNull(prop.labels)
                                                 && typeof prop.labels[indexes[0]] === 'string'
                                                )
                                                    ? prop.labels[angles[i].dataset]
                                                    : null;

                    // Adjust the indexes in the case of non-equi-angular charts
                    if (prop.variant === 'non-equi-angular') {
                        angles[i].dataset = angles[i].sequentialIndex;
                        angles[i].index   = 0;
                        angles[i].label   = (RGraph.isArray(prop.labels) && typeof prop.labels[angles[i].dataset] === 'string')
                                                ? prop.labels[angles[i].dataset]
                                                : null;
                    }

                    return {
                         object: this,
                              x: angles[i][4],
                              y: angles[i][5],
                     angleStart: angles[i][0],
                       angleEnd: angles[i][1],
                    radiusStart: angles[i][2],
                      radiusEnd: angles[i][3],
                        dataset: angles[i].dataset,
                          index: angles[i].index,
                sequentialIndex: angles[i][6],
                          label: angles[i].label,
                        tooltip: typeof tooltip === 'string' ? tooltip : null
                    };
                }
            }

            return null;
        };








        //
        // Returns any exploded for a particular segment
        //
        this.getExploded = function (index, startAngle, endAngle, exploded)
        {
            var explodedx, explodedy;
    
            //
            // Retrieve any exploded - the exploded can be an array of numbers or a single number
            // (which is applied to all segments)
            //
            if (typeof exploded == 'object' && typeof exploded[index] == 'number') {
                explodedx = Math.cos(((endAngle - startAngle) / 2) + startAngle) * exploded[index];
                explodedy = Math.sin(((endAngle - startAngle) / 2) + startAngle) * exploded[index];
            
            } else if (typeof exploded == 'number') {
                explodedx = Math.cos(((endAngle - startAngle) / 2) + startAngle) * exploded;
                explodedy = Math.sin(((endAngle - startAngle) / 2) + startAngle) * exploded;
    
            } else {
                explodedx = 0;
                explodedy = 0;
            }
            
            return [explodedx, explodedy];
        };








        //
        // This function facilitates the installation of tooltip event listeners if
        // tooltips are defined.
        //
        this.allowTooltips = function ()
        {
            // Preload any tooltip images that are used in the tooltips
            RGraph.preLoadTooltipImages(this);
    
    
            //
            // This installs the window mousedown event listener that lears any
            // highlight that may be visible.
            //
            RGraph.installWindowMousedownTooltipListener(this);
    
    
            //
            // This installs the canvas mousemove event listener. This function
            // controls the pointer shape.
            //
            RGraph.installCanvasMousemoveTooltipListener(this);
    
    
            //
            // This installs the canvas mouseup event listener. This is the
            // function that actually shows the appropriate tooltip (if any).
            //
            RGraph.installCanvasMouseupTooltipListener(this);
        };








        //
        // Each object type has its own Highlight() function which highlights the appropriate shape
        // 
        // @param object shape The shape to highlight
        //
        this.highlight = function (shape)
        {
            if (prop.tooltipsHighlight) {

                if (typeof prop.highlightStyle === 'function'){
                    (prop.highlightStyle)(shape);
                    return;
                }









                // Highlight all of the rects except this one - essentially an inverted highlight
                if (typeof prop.highlightStyle === 'string' && prop.highlightStyle === 'invert') {
                    for (var i=0; i<this.angles.length; ++i) {

                        if (i !== shape.sequentialIndex) {

                            // Add the new segment highlight
                            this.path(
                                'b m % % a % % % % % false',
                                this.angles[i][4], this.angles[i][5],this.angles[i][4], this.angles[i][5],this.angles[i][3],this.angles[i][0], this.angles[i][1]
                            );
            
                            if (this.angles[i][2] > 0) {
                                this.path(
                                    'a % % % % % true',
                                    this.angles[i][4], this.angles[i][5],this.angles[i][2],this.angles[i][1], this.angles[i][0]
                                );

                            } else {
                                this.path(
                                    'l % %',
                                    this.angles[i][4], this.angles[i][5]
                                );
                            }
                            
                            this.path(
                                'c s % f %',
                                prop.highlightStroke, prop.highlightFill
                            );
                        }
                    }
                    
                    return;
                }














                // Add the new segment highlight
                this.path('b a % % % % % false',shape.x, shape.y, shape.radiusEnd, shape.angleStart, shape.angleEnd);

                if (shape.radiusStart > 0) {
                    this.path('a % % % % % true',shape.x, shape.y, shape.radiusStart, shape.angleEnd, shape.angleStart);
                } else {
                    this.path('l % %',shape.x, shape.y);
                }
                
                this.path('c s % f %', prop.highlightStroke, prop.highlightFill);
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
    
            // Work out the radius
            var radius = RGraph.getHypLength(this.centerx, this.centery, mouseXY[0], mouseXY[1]);

            // Account for the 3D stretching effect
            if (prop.variant.indexOf('3d') !== -1) {
                radius /= -1;
                
                // Can be 100 because there's a radius check done as well
                var additional3D = 100;

            } else {
                var additional3D = 0;
            }

            if (
                   mouseXY[0] > (this.centerx - this.radius - additional3D)
                && mouseXY[0] < (this.centerx + this.radius + additional3D)
                && mouseXY[1] > (this.centery - this.radius)
                && mouseXY[1] < (this.centery + this.radius)
                && radius <= this.radius
                ) {
    
                return this;
            }
        };








        //
        // This method gives you the relevant radius for a particular value
        // 
        // @param number value The relevant value to get the radius for
        //
        this.getRadius = function (value)
        {
            // Range checking (the Rose minimum is always 0)
            if (value < 0 || value > this.max) {
                return null;
            }
            
            var r = (value / this.max) * this.radius;
            
            return r;
        };








        //
        // This allows for easy specification of gradients
        //
        this.parseColors = function ()
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (this.original_colors.length === 0) {
                this.original_colors.colors          = RGraph.arrayClone(prop.colors);
                this.original_colors.keyColors       = RGraph.arrayClone(prop.keyColors);
                this.original_colors.highlightStroke = RGraph.arrayClone(prop.highlightStroke);
                this.original_colors.highlightFill   = RGraph.arrayClone(prop.highlightFill);
            }


            for (var i=0; i<prop.colors.length; ++i) {
                prop.colors[i] = this.parseSingleColorForGradient(prop.colors[i]);
            }
    
            //
            // Key colors
            //
            if (!RGraph.isNull(prop.keyColors)) {
                for (var i=0; i<prop.keyColors.length; ++i) {
                    prop.keyColors[i] = this.parseSingleColorForGradient(prop.keyColors[i]);
                }
            }

            prop.highlightFill           = this.parseSingleColorForGradient(prop.highlightFill);
            prop.highlightStroke         = this.parseSingleColorForGradient(prop.highlightStroke);
            prop.segmentHighlightStroke = this.parseSingleColorForGradient(prop.segmentHighlightStroke);
            prop.segmentHighlightFill   = this.parseSingleColorForGradient(prop.segmentHighlightFill);
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
                //var grad = context.createLinearGradient(0,0,canvas.width,0);
                var grad = this.context.createRadialGradient(this.centerx, this.centery, 0, this.centerx, this.centery, this.radius);
    
                var diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RGraph.trim(parts[0]));
    
                for (var j=1; j<parts.length; ++j) {
                    grad.addColorStop(j * diff, RGraph.trim(parts[j]));
                }
            }

            return grad ? grad : color;
        };








        //
        // This function handles highlighting an entire data-series for the interactive
        // key
        // 
        // @param int index The index of the data series to be highlighted
        //
        this.interactiveKeyHighlight = function (index)
        {
            var segments = this.angles2;
            
            for (var i=0; i<this.angles2.length; i+=1) {
                this.context.beginPath();
                    this.context.lineWidth = 2;
                    this.context.fillStyle = prop.keyInteractiveHighlightChartFill;
                    this.context.strokeStyle = prop.keyInteractiveHighlightChartStroke;
                    this.context.arc(segments[i][index][4], segments[i][index][5], segments[i][index][2], segments[i][index][0], segments[i][index][1], false);
                    this.context.arc(segments[i][index][4], segments[i][index][5], segments[i][index][3], segments[i][index][1], segments[i][index][0], true);
                this.context.closePath();
                this.context.fill();
                this.context.stroke();
            }

            return;
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
        // This function runs once only
        // (put at the end of the file (before any effects))
        //
        this.firstDrawFunc = function ()
        {
        };








        //
        // Rose chart explode
        // 
        // Explodes the Rose chart - gradually incrementing the size of the explode property
        // 
        // @param object     Optional options for the effect. You can pass in frames here - such as:
        //                   myRose.roundRobin({frames: 60}; function () {alert('Done!');})
        // @param function   A callback function which is called when the effect is finished
        //
        this.explode = function ()
        {
            var obj         = this;
            var opt         = arguments[0] || {};
            var callback    = arguments[1] || function (){};
            var frames      = opt.frames ? opt.frames : 30;
            var frame       = 0;
            var explodedMax = Math.max(this.canvas.width, this.canvas.height);
            var exploded    = Number(this.get('exploded'));
    
    
    
    
            function iterator ()
            {
                exploded =  (frame / frames) * explodedMax;

                // Set the new value
                obj.set('exploded', exploded);
    
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);
    
                if (frame++ < frames) {
                    RGraph.Effects.updateCanvas(iterator);
                } else  {
                    callback(obj);
                }
            }




            iterator();
            
            return this;
        };








        //
        // RoundRobin
        // 
        // This effect is similar to the Pie chart RoundRobin effect
        // 
        // @param object     Optional options for the effect. You can pass in frames here - such as:
        //                   myRose.roundRobin({frames: 60}; function () {alert('Done!');})
        // @param function   A callback function which is called when the effect is finished
        //
        this.roundRobin = function ()
        {
            var obj             = this;
            var opt             = arguments[0] || {}
            var frames          = opt.frames || 30;
            var frame           = 0;
            var original_margin = prop.margin;
            var margin          = (360 / this.data.length) / 2;
            var callback        = arguments[1] || function () {};
    
            this.set('margin', margin);
            this.set('animationRoundrobinFactor', 0);
    
            function iterator ()
            {
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);

                if (frame++ < frames) {
                    obj.set('animationRoundrobinFactor', frame / frames);
                    obj.set('margin', (frame / frames) * original_margin);
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    obj.set('animationRoundrobinFactor', 1);
                    obj.set('margin', original_margin);

                    callback(obj);
                }
            }
            
            iterator();
            
            return this;
        };








        //
        // Rose chart implode
        // 
        // Implodes the Rose chart - gradually decreasing the size of the explode property. It starts at the largest of
        // the canvas width./height
        // 
        // @param object     Optional options for the effect. You can pass in frames here - such as:
        //                   myRose.implode({frames: 60}; function () {alert('Done!');})
        // @param function   A callback function which is called when the effect is finished
        //
        this.implode = function ()
        {
            var obj         = this;
            var opt         = arguments[0] || {};
            var callback    = arguments[1] || function (){};
            var frames      = opt.frames || 30;
            var frame       = 0;
            var explodedMax = Math.max(this.canvas.width, this.canvas.height);
            var exploded    = explodedMax;
    
    
    
            function iterator ()
            {
                exploded =  explodedMax - ((frame / frames) * explodedMax);

                // Set the new value
                obj.set('exploded', exploded);
    
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);

                if (frame++ < frames) {
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    RGraph.clear(obj.canvas);
                    RGraph.redrawCanvas(obj.canvas);
                    callback(obj);
                }
            }
            
            iterator();

            return this;
        };








        //
        // Rose chart Grow
        // 
        // This effect gradually increases the size of the Rose chart
        // 
        // @param object     Optional options for the effect. You can pass in frames here - such as:
        //                   myRose.grow({frames: 60}; function () {alert('Done!');})
        // @param function   A callback function which is called when the effect is finished
        //
        this.grow = function ()
        {
            var obj      = this;
            var opt      = arguments[0] || {};
            var callback = arguments[1] || function (){};
            var frames   = opt.frames || 30;
            var frame    = 0;

            function iterator ()
            {
                obj.set('animationGrowMultiplier', frame / frames);
    
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);

                if (frame < frames) {
                    frame++;
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    callback(obj);
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
            var indexes = RGraph.sequentialIndexToGrouped(opt.index, this.data);
            var value   = this.data_arr[opt.index];
            var values  = typeof this.data[indexes[0]] === 'number' ? [this.data[indexes[0]]] : this.data[indexes[0]];

            if (prop.variant === 'non-equi-angular' && RGraph.isArray(this.data[indexes[0]])) {
                value   = this.data[opt.index][0];
                value2  = this.data[opt.index][1];
                indexes = [opt.index, 0];
            }

            return {
                  index: indexes[1],
                dataset: indexes[0],
        sequentialIndex: opt.index,
                  value: value,
                 value2: typeof value2 === 'number' ? value2 : null,
                 values: values
            };
        };








        //
        // A worker function that returns the correct color/label/value
        //
        // @param object specific The indexes that are applicable
        // @param number index    The appropriate index
        //
        this.tooltipsFormattedCustom = function (specific, index, colors)
        {
            var color = prop.colors[index];
            
            // Accommodate colorsSequential
            if (prop.colorsSequential) {
                color = colors[specific.sequential];
            }
            
            // Different variations of the Rose chart

            // REGULAR CHART
            if (typeof this.data[specific.dataset] === 'number') {
                
                var label = prop.tooltipsFormattedKeyLabels[specific.dataset] || '';
                var color = prop.colors[0];
                
                if (prop.tooltipsFormattedKeyColors && prop.tooltipsFormattedKeyColors[0]) {
                    color = prop.tooltipsFormattedKeyColors[0];
                }


            // NON-EQUI-ANGULAR CHART
            } else if (typeof this.data[specific.dataset] === 'object' && prop.variant === 'non-equi-angular') {

                // Don't show the second value on a non-equi-angular chart
                if (index === 0) {

                    var color = colors[specific.index];
                    var value = this.data[specific.dataset][0];
                    
                    // Allow for specific tooltip key colors
                    if (prop.tooltipsFormattedKeyColors && prop.tooltipsFormattedKeyColors[specific.index]) {
                        color = prop.tooltipsFormattedKeyColors[specific.index];
                    }
                } else {
                    var skip = true;
                }
            
            // STACKED CHART
            } else if (typeof this.data[specific.dataset] === 'object' && prop.variant !== 'non-equi-angular') {
                // Allow for specific tooltip key colors
                if (prop.tooltipsFormattedKeyColors && prop.tooltipsFormattedKeyColors[index]) {
                    color = prop.tooltipsFormattedKeyColors[index];
                }
            }

            //label = ( (typeof prop.tooltipsFormattedKeyLabels === 'object' && typeof prop.tooltipsFormattedKeyLabels[specific.index] === 'string') ? prop.tooltipsFormattedKeyLabels[specific.index] : '');

            return {
                continue: skip,
                label: label,
                color: color,
                value: value
            };
        };








        //
        // Register this object
        //
        RGraph.register(this);








       //
        // This is the 'end' of the constructor so if the first argument
        // contains configuration data - handle that.
        //
        RGraph.parseObjectStyleConfig(this, conf.options);
    };