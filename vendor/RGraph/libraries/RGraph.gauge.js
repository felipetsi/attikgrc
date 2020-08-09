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
    // The line chart constructor
    //
    RGraph.Gauge = function (conf)
    {
        var id     = conf.id,
            canvas = document.getElementById(id),
            min    = conf.min,
            max    = conf.max,
            value  = conf.value;
    
        this.id                = id;
        this.canvas            = canvas;
        this.context           = this.canvas.getContext ? this.canvas.getContext("2d", {alpha: (typeof id === 'object' && id.alpha === false) ? false : true}) : null;
        this.canvas.__object__ = this;
        this.type              = 'gauge';
        this.min               = RGraph.stringsToNumbers(min);
        this.max               = RGraph.stringsToNumbers(max);
        this.value             = RGraph.stringsToNumbers(value);
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



        //
        // Range checking.
        // 
        // As 4.63 it now saves the original value
        //
        this.valueOriginal = this.value;

        if (typeof this.value === 'object') {
            for (var i=0; i<this.value.length; ++i) {
                if (this.value[i] > this.max) this.value[i] = max;
                if (this.value[i] < this.min) this.value[i] = min;
            }
        } else {
            if (this.value > this.max) this.value = max;
            if (this.value < this.min) this.value = min;
        }


        // Coniguration properties
        this.properties =
        {
            anglesStart:  null,
            anglesEnd:    null,
            
            centerx:       null,
            centery:       null,
            radius:        null,
            
            marginLeft:   35,
            marginRight:  35,
            marginTop:    35,
            marginBottom: 35,
            
            borderWidth:  10,

            textFont:                     'Arial, Verdana, sans-serif',
            textSize:                     12,
            textColor:                    '#666',
            textBold:                     false,
            textItalic:                   false,
            textAccessible:               true,
            textAccessibleOverflow:      'visible',
            textAccessiblePointerevents: false,

            titleTop:          '',
            titleTopFont:     null,
            titleTopSize:     null,
            titleTopColor:    null,
            titleTopBold:     null,
            titleTopItalic:   null,
            titleTopPos:      null,
            
            titleBottom:          '',
            titleBottomFont:      null,
            titleBottomSize:      null,
            titleBottomColor:     null,
            titleBottomBold:      null,
            titleBottomItalic:    null,
            titleBottomPos:       null,
            
            backgroundColor:               'white',
            backgroundGradient:            false,

            scaleDecimals:             0,
            scalePoint:                '.',
            scaleThousand:             ',',
            scaleUnitsPre:            '',
            scaleUnitsPost:           '',
            scalePoint:                '.',
            scaleThousand:             ',',
            
            labelsCount:                      5,
            labelsCentered:                   false,
            labelsOffsetRadius:              0,
            labelsOffsetAngle:               0,
            labelsSpecific:                   null,
            labelsOffsetx:                    0,
            labelsOffsety:                    0,
            labelsFont:                       null,
            labelsSize:                       null,
            labelsColor:                      null,
            labelsBold:                       null,
            labelsItalic:                     null,
            labelsValue:                 false,
            labelsValueYPos:           0.5,
            labelsValueUnitsPre:       '',
            labelsValueUnitsPost:      '',
            labelsValueBounding:        true,
            labelsValueBoundingFill:   'white',
            labelsValueBoundingStroke: 'black',
            labelsValueFont:            null,
            labelsValueSize:            null,
            labelsValueColor:           null,
            labelsValueItalic:          null,
            labelsValueBold:            null,
            labelsValueDecimals:        null,


            colorsRedStart:      0.9 * this.max,
            colorsRedColor:      '#DC3912',
            colorsRedWidth:      10,
            colorsYellowColor:   '#FF9900',
            colorsYellowWidth:   10,
            colorsGreenEnd:      0.7 * this.max,
            colorsGreenColor:    'rgba(0,0,0,0)',
            colorsGreenWidth:    10,
            colorsRanges:        null,

            needleSize:    null,
            needleTail:    false,
            needleColors:   ['#D5604D', 'red', 'green', 'yellow'],
            needleType:     'triangle',
            needleWidth:     7,

            borderOuter:     '#ccc',
            borderInner:     '#f1f1f1',
            borderOutline:   'black',
            borderGradient:  false,

            centerpinColor:  'blue',
            centerpinRadius: null,

            tickmarksSmall:        25,
            tickmarksSmallColor:  'black',
            tickmarksMedium:       0,
            tickmarksMediumColor: 'black',
            tickmarksLarge:        5,
            tickmarksLargeColor:  'black',

            adjustable:       false,

            shadow:           true,
            shadowColor:     'gray',
            shadowOffsetx:   0,
            shadowOffsety:   0,
            shadowBlur:      15,

            clearto:   'rgba(0,0,0,0)'
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
        // An all encompassing accessor
        // 
        // @param string name The name of the property
        // @param mixed value The value of the property
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
        // An all encompassing accessor
        // 
        // @param string name The name of the property
        //
        this.get = function (name)
        {
            return prop[name];
        };








        //
        // The function you call to draw the line chart
        // 
        // @param bool An optional bool used internally to ditinguish whether the
        //             line chart is being called by the bar chart
        //
        this.draw = function ()
        {
            //
            // Fire the onbeforedraw event
            //
            RGraph.fireCustomEvent(this, 'onbeforedraw');
    
    
    
            //
            // Store the value (for animation primarily
            //
            this.currentValue = this.value;



            //
            // Make the margins easy ro access
            //            
            this.marginLeft   = prop.marginLeft;
            this.marginRight  = prop.marginRight;
            this.marginTop    = prop.marginTop;
            this.marginBottom = prop.marginBottom;
            
            this.centerx = ((this.canvas.width - this.marginLeft - this.marginRight) / 2) + this.marginLeft;
            this.centery = ((this.canvas.height - this.marginTop - this.marginBottom) / 2) + this.marginTop;
            this.radius  = Math.min(
                ((this.canvas.width - this.marginLeft - this.marginRight) / 2),
                ((this.canvas.height - this.marginTop - this.marginBottom) / 2)
            );
            this.startAngle = prop.anglesStart ? prop.anglesStart : (RGraph.HALFPI / 3) + RGraph.HALFPI;
            this.endAngle   = prop.anglesEnd ? prop.anglesEnd : RGraph.TWOPI + RGraph.HALFPI - (RGraph.HALFPI / 3);
            
            
            //
            // Reset this so it doesn't keep growing
            //
            this.coordsText = [];
    
    
    
            //
            // You can now override the positioning and radius if you so wish.
            //
            if (typeof prop.centerx == 'number') this.centerx = prop.centerx;
            if (typeof prop.centery == 'number') this.centery = prop.centery;
            if (typeof prop.radius == 'number')  this.radius  = prop.radius;
    
            //
            // Parse the colors. This allows for simple gradient syntax
            //
            if (!this.colorsParsed) {
                this.parseColors();
                
                // Don't want to do this again
                this.colorsParsed = true;
            }
    
    
            // This has to be in the constructor
            this.centerpinRadius = 0.16 * this.radius;
            
            if (typeof prop.centerpinRadius == 'number') {
                this.centerpinRadius = prop.centerpinRadius;
            }
    
    
    
            //
            // Setup the context menu if required
            //
            if (prop.contextmenu) {
                RGraph.showContext(this);
            }
    
    
    
            // DRAW THE CHART HERE
            this.drawBackGround();
            this.drawGradient();
            this.drawColorBands();
            this.drawSmallTickmarks();
            this.drawMediumTickmarks();
            this.drawLargeTickmarks();
            this.drawLabels();
            this.drawTopTitle();
            this.drawBottomTitle();
    
            if (typeof this.value == 'object') {
                for (var i=0; i<this.value.length; ++i) {
                    this.drawNeedle(this.value[i], prop.needleColors[i], i);
                }
            } else {
                this.drawNeedle(this.value, prop.needleColors[0], 0);
            }
    
            this.drawCenterpin();
            
            //
            // This function enables resizing
            //
            if (prop.resizable) {
                RGraph.allowResizing(this);
            }
    
    
            //
            // This installs the event listeners
            //
            RGraph.installEventListeners(this);
    

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
        // Draw the background
        //
        this.drawBackGround = function ()
        {
            // Shadow //////////////////////////////////////////////
            if (prop.shadow) {
                RGraph.setShadow(
                    this,
                    prop.shadowColor,
                    prop.shadowOffsetx,
                    prop.shadowOffsety,
                    prop.shadowBlur
                );
            }
            
            this.context.beginPath();
                this.context.fillStyle = prop.backgroundColor;
                //this.context.moveTo(this.centerx, this.centery)
                this.context.arc(this.centerx, this.centery, this.radius, 0, RGraph.TWOPI, 0);
            this.context.fill();
            
            // Turn off the shadow
            RGraph.noShadow(this);
            // Shadow //////////////////////////////////////////////
    
    
            var grad = this.context.createRadialGradient(this.centerx + 50, this.centery - 50, 0, this.centerx + 50, this.centery - 50, 150);
            grad.addColorStop(0, '#eee');
            grad.addColorStop(1, 'white');
    
            var borderWidth = prop.borderWidth;
    
            this.context.beginPath();
                this.context.fillStyle = prop.backgroundColor;
                this.context.arc(this.centerx, this.centery, this.radius, 0, RGraph.TWOPI, 0);
            this.context.fill();
    
            //
            // Draw the gray circle
            //
            this.context.beginPath();
                this.context.fillStyle = prop.borderOuter;
                this.context.arc(this.centerx, this.centery, this.radius, 0, RGraph.TWOPI, 0);
            this.context.fill();
    
            //
            // Draw the light gray inner border
            //
            this.context.beginPath();
                this.context.fillStyle = prop.borderInner;
                this.context.arc(this.centerx, this.centery, this.radius - borderWidth, 0, RGraph.TWOPI, 0);
            this.context.fill();
    
    
    
            // Draw the white circle inner border
            this.context.beginPath();
                this.context.fillStyle = prop.backgroundColor;
                this.context.arc(this.centerx, this.centery, this.radius - borderWidth - 4, 0, RGraph.TWOPI, 0);
            this.context.fill();
    
    
    
            // Draw the circle background. Can be any colour now.
            this.context.beginPath();
                this.context.fillStyle = prop.backgroundColor;
                this.context.arc(this.centerx, this.centery, this.radius - borderWidth - 4, 0, RGraph.TWOPI, 0);
            this.context.fill();
    
            if (prop.backgroundGradient) {

                // Draw a partially transparent gradient that sits on top of the background
                this.context.beginPath();
                    this.context.fillStyle = RGraph.radialGradient({
                        object: this,
                        x1:     this.centerx - this.radius,
                        y1:     this.centery - this.radius,
                        r1:     0,
                        x2:     this.centerx - (this.radius / 2),
                        y2:     this.centery - (this.radius / 2),
                        r2:     this.radius,
                        colors: [
                            'rgba(255,255,255,0.2)',
                            'rgba(0,0,0,0.1)'
                        ]
                    });
                    this.context.arc(this.centerx, this.centery, this.radius - borderWidth - 4, 0, RGraph.TWOPI, 0);
                this.context.fill();
            }
    
    
    
            // Draw a black border around the chart
            this.context.beginPath();
                this.context.strokeStyle = prop.borderOutline;
                this.context.arc(this.centerx, this.centery, this.radius, 0, RGraph.TWOPI, 0);
            this.context.stroke();
        };








        //
        // This function draws the smaller tickmarks
        //
        this.drawSmallTickmarks = function ()
        {
            var numTicks = prop.tickmarksSmall;
            this.context.lineWidth = 1;
    
            for (var i=0; i<=numTicks; ++i) {
                this.context.beginPath();
                    this.context.strokeStyle = prop.tickmarksSmallColor;
                    var a = (((this.endAngle - this.startAngle) / numTicks) * i) + this.startAngle;
                    this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10, a, a + 0.00001, 0);
                    this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10 - 5, a, a + 0.00001, 0);
                this.context.stroke();
            }
        };








        //
        // This function draws the medium sized tickmarks
        //
        this.drawMediumTickmarks = function ()
        {
            if (prop.tickmarksMedium) {
    
                var numTicks = prop.tickmarksMedium;
                this.context.lineWidth = 3;
                this.context.lineCap   = 'round';
                this.context.strokeStyle = prop.tickmarksMediumColor;
        
                for (var i=0; i<=numTicks; ++i) {
                    this.context.beginPath();
                        var a = (((this.endAngle - this.startAngle) / numTicks) * i) + this.startAngle + (((this.endAngle - this.startAngle) / (2 * numTicks)));
                        
                        if (a > this.startAngle && a< this.endAngle) {
                            this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10, a, a + 0.00001, 0);
                            this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10 - 6, a, a + 0.00001, 0);
                        }
                    this.context.stroke();
                }
            }
        };








        //
        // This function draws the large, bold tickmarks
        //
        this.drawLargeTickmarks = function ()
        {
            var numTicks = prop.tickmarksLarge;

            this.context.lineWidth = 3;
            this.context.lineCap   = 'round';
    
            for (var i=0; i<=numTicks; ++i) {
                this.context.beginPath();
                    this.context.strokeStyle = prop.tickmarksLargeColor;
                    var a = (((this.endAngle - this.startAngle) / numTicks) * i) + this.startAngle;
                    this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10, a, a + 0.00001, 0);
                    this.context.arc(this.centerx, this.centery, this.radius - prop.borderWidth - 10 - 10, a, a + 0.00001, 0);
                this.context.stroke();
            }
        };








        //
        // This function draws the centerpin
        //
        this.drawCenterpin = function ()
        {
            var offset = 6;
    
            var grad = this.context.createRadialGradient(this.centerx + offset, this.centery - offset, 0, this.centerx + offset, this.centery - offset, 25);
            grad.addColorStop(0, '#ddf');
            grad.addColorStop(1, prop.centerpinColor);
    
            this.context.beginPath();
                this.context.fillStyle = grad;
                this.context.arc(this.centerx, this.centery, this.centerpinRadius, 0, RGraph.TWOPI, 0);
            this.context.fill();
        };








        //
        // This function draws the labels
        //
        this.drawLabels = function ()
        {
            this.context.fillStyle = prop.textColor;
            
            var font    = prop.textFont,
                size    = prop.textSize,
                num     = prop.labelsSpecific ? (prop.labelsSpecific.length - 1) : prop.labelsCount,
                offsetx = prop.labelsOffsetx,
                offsety = prop.labelsOffsety,
                offseta = prop.labelsOffsetAngle;

            var textConf = RGraph.getTextConf({
                object: this,
                prefix: 'labels'
            });

            this.context.beginPath();
                if (num) {
                    for (var i=0; i<=num; ++i) {
                        var hyp = (this.radius - 25 - prop.borderWidth) - prop.labelsOffsetRadius;
                        var a   = (this.endAngle - this.startAngle) / num
                            a   = this.startAngle + (i * a);
                            a  -= RGraph.HALFPI;
                            a += offseta;


                        var x = this.centerx - (Math.sin(a) * hyp);
                        var y = this.centery + (Math.cos(a) * hyp);

                        var hAlign = x > this.centerx ? 'right' : 'left';
                        var vAlign = y > this.centery ? 'bottom' : 'top';
                        
                        // This handles the label alignment when the label is on a PI/HALFPI boundary
                        if (a == RGraph.HALFPI) {
                            vAlign = 'center';
                        } else if (a == RGraph.PI) {
                            hAlign = 'center';
                        } else if (a == (RGraph.HALFPI + RGraph.PI) ) {
                            vAlign = 'center';
                        }
                        
                        //
                        // Can now force center alignment
                        //
                        if (prop.labelsCentered) {
                            hAlign = 'center';
                            vAlign = 'center';
                        }
                        
                        var value = (((this.max - this.min) * (i / num)) + this.min);
        

                        RGraph.text({
                        
                           object: this,

                             font: textConf.font,
                             size: textConf.size,
                            color: textConf.color,
                             bold: textConf.bold,
                           italic: textConf.italic,
                           
                            x:      x + offsetx,
                            y:      y + offsety,
                            text:   prop.labelsSpecific ? prop.labelsSpecific[i] : RGraph.numberFormat({
                                object:    this,
                                number:    value.toFixed(prop.scaleDecimals),
                                unitspre:  prop.scaleUnitsPre,
                                unitspost: prop.scaleUnitsPost,
                                point:     prop.scalePoint,
                                thousand:  prop.scaleThousand
                            }),
                            halign: hAlign,
                            valign: vAlign,
                            tag:    prop.labelsSpecific ? 'labels.specific' : 'labels'
                        });
                    }
                }
            this.context.fill();





            //
            // Draw the textual value if requested
            //
            if (prop.labelsValue) {
    
                var x              = this.centerx,
                    y              = this.centery + (prop.labelsValueYPos * this.radius),
                    units_pre      = typeof prop.labelsValueUnitsPre == 'string' ? prop.labelsValueUnitsPre : prop.scaleUnitsPre,
                    units_post     = typeof prop.labelsValueUnitsPost == 'string' ? prop.labelsValueUnitsPost : prop.scaleUnitsPost,

                    bounding       = prop.labelsValueBounding,
                    boundingFill   = prop.labelsValueBoundingFill,
                    boundingStroke = prop.labelsValueBoundingStroke,
                    decimals       = typeof prop.labelsValueDecimals === 'number' ? prop.labelsValueDecimals : prop.scaleDecimals;
            
                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'labelsValue'
                });


                if (typeof this.value === 'number') {

                    var value = parseFloat(prop.valueTextActual ? this.valueOriginal : this.value);

                    var text = RGraph.numberFormat({
                        object:    this,
                        number:    value.toFixed(decimals),
                        unitspre:  units_pre,
                        unitspost: units_post
                    });

                } else {
                
                    var text = [];
                
                    for (var i=0; i<this.value.length; ++i) {
                        text[i] = RGraph.numberFormat({
                            object:    this,
                            number:    this.value[i].toFixed(decimals),
                            unitspre:  units_pre,
                            unitspost: units_post
                        });
                    }
                    
                    text = text.join(', ');
                }

                RGraph.text({
                
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:                  x,
                    y:                  y,
                    text:               text,
                    halign:             'center',
                    valign:             'center',
                    bounding:           bounding,
                    'bounding.fill':    boundingFill,
                    'bounding.stroke':  boundingStroke,
                    tag:                'value.text'
                });
            }
        };








        //
        // This function draws the top title
        //
        this.drawTopTitle = function ()
        {
            var x = this.centerx;
            var y = this.centery - 25;
            
            // Totally override the calculated positioning
            if (typeof prop.titleTopPos == 'number') {
                y = this.centery - (this.radius * prop.titleTopPos);
            }

            var textConf = RGraph.getTextConf({
                object: this,
                prefix: 'titleTop'
            });

            if (prop.titleTop) {
                this.context.fillStyle = prop.titleTopColor;
                RGraph.text({
                
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:      x,
                    y:      y,
                    text:   String(prop.titleTop),
                    halign: 'center',
                    valign: 'bottom',
                    tag:    'title.top'
                });
            }
        };








        //
        // This function draws the bottom title
        //
        this.drawBottomTitle = function ()
        {
            var x = this.centerx;
            var y = this.centery + this.centerpinRadius + 10;
    
            // Totally override the calculated positioning
            if (typeof prop.titleBottomPos == 'number') {
                y = this.centery + (this.radius * prop.titleBottomPos);
            }
    
            if (prop.titleBottom) {
                this.context.fillStyle = prop.titleBottomColor;

                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'titleBottom'
                });

                RGraph.text({
                
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:      x,
                    y:      y,
                    text:   String(prop.titleBottom),
                    halign: 'center',
                    valign: 'top',
                    tag:    'title.bottom'
                });
            }
        };








        //
        // This function draws the Needle
        // 
        // @param number value The value to draw the needle for
        //
        this.drawNeedle = function (value, color, index)
        {
            var type = prop.needleType;

            this.context.lineWidth   = 0.5;
            this.context.strokeStyle = 'gray';
            this.context.fillStyle   = color;
    
            var angle = (this.endAngle - this.startAngle) * ((value - this.min) / (this.max - this.min));
                angle += this.startAngle;
    
    
            // Work out the size of the needle
            if (   typeof prop.needleSize == 'object'
                && prop.needleSize
                && typeof prop.needleSize[index] == 'number') {
    
                var size = prop.needleSize[index];
    
            } else if (typeof prop.needleSize == 'number') {
                var size = prop.needleSize;
    
            } else {
                var size = this.radius - 25 - prop.borderWidth;
            }
    
            
    
            if (type == 'line') {
    
                this.context.beginPath();
                
                    this.context.lineWidth = prop.needleWidth;
                    this.context.strokeStyle = color;
                    
                    this.context.arc(this.centerx,
                        this.centery,
                        size,
                        angle,
                        angle + 0.0001,
                        false
                    );
                    
                    this.context.lineTo(this.centerx, this.centery);
                    
                    if (prop.needleTail) {
                        this.context.arc(this.centerx, this.centery, this.radius * 0.2  , angle + RGraph.PI, angle + 0.00001 + RGraph.PI, false);
                    }
                    
                    this.context.lineTo(this.centerx, this.centery);
        
                this.context.stroke();
                //this.context.fill();
    
            } else {
        
                this.context.beginPath();
                    this.context.arc(this.centerx, this.centery, size, angle, angle + 0.00001, false);
                    this.context.arc(this.centerx, this.centery, this.centerpinRadius * 0.5, angle + RGraph.HALFPI, angle + 0.00001 + RGraph.HALFPI, false);
                    
                    if (prop.needleTail) {
                        this.context.arc(this.centerx, this.centery, this.radius * 0.2  , angle + RGraph.PI, angle + 0.00001 + RGraph.PI, false);
                    }
        
                    this.context.arc(this.centerx, this.centery, this.centerpinRadius * 0.5, angle - RGraph.HALFPI, angle - 0.00001 - RGraph.HALFPI, false);
                this.context.stroke();
                this.context.fill();
                
                //
                // Store the angle in an object variable
                //
                this.angle = angle;
            }
        };








        //
        // This draws the green background to the tickmarks
        //
        this.drawColorBands = function ()
        {
            if (RGraph.isArray(prop.colorsRanges)) {
    
                var ranges = prop.colorsRanges;
    
                for (var i=0; i<ranges.length; ++i) {
    
                    this.context.fillStyle = ranges[i][2];
                    this.context.lineWidth = 0;

                    this.context.beginPath();
                        this.context.arc(
                            this.centerx,
                            this.centery,
                            this.radius - 10 - prop.borderWidth,
                            (((ranges[i][0] - this.min) / (this.max - this.min)) * (this.endAngle - this.startAngle)) + this.startAngle,
                            (((ranges[i][1] - this.min) / (this.max - this.min)) * (this.endAngle - this.startAngle)) + this.startAngle,
                            false
                        );
    
                        this.context.arc(
                            this.centerx,
                            this.centery,
                            this.radius - 10 - prop.borderWidth - (typeof ranges[i][3] === 'number' ? ranges[i][3] : 10),
                            (((ranges[i][1] - this.min) / (this.max - this.min)) * (this.endAngle - this.startAngle)) + this.startAngle,
                            (((ranges[i][0] - this.min) / (this.max - this.min)) * (this.endAngle - this.startAngle)) + this.startAngle,
                            true
                        );
                    this.context.closePath();
                    this.context.fill();
                }
    
                return;
            }

            //
            // Draw the GREEN region
            //
            this.context.strokeStyle = prop.colorsGreenColor;
            this.context.fillStyle = prop.colorsGreenColor;
            
            var greenStart = this.startAngle;
            var greenEnd   = this.startAngle + (this.endAngle - this.startAngle) * ((prop.colorsGreenEnd - this.min) / (this.max - this.min))

            this.context.beginPath();
                this.context.arc(this.centerx, this.centery, this.radius - 10 - prop.borderWidth, greenStart, greenEnd, false);
                this.context.arc(this.centerx, this.centery, this.radius - (10 + prop.colorsGreenWidth) - prop.borderWidth, greenEnd, greenStart, true);
            this.context.fill();
    
    
    
    
    
            //
            // Draw the YELLOW region
            //
            this.context.strokeStyle = prop.colorsYellowColor;
            this.context.fillStyle = prop.colorsYellowColor;
            
            var yellowStart = greenEnd;
            var yellowEnd   = this.startAngle + (this.endAngle - this.startAngle) * ((prop.colorsRedStart - this.min) / (this.max - this.min))
    
            this.context.beginPath();
                this.context.arc(this.centerx, this.centery, this.radius - 10 - prop.borderWidth, yellowStart, yellowEnd, false);
                this.context.arc(this.centerx, this.centery, this.radius - (10 + prop.colorsYellowWidth) - prop.borderWidth, yellowEnd, yellowStart, true);
            this.context.fill();
    
    
    
    
    
            //
            // Draw the RED region
            //
            this.context.strokeStyle = prop.colorsRedColor;
            this.context.fillStyle = prop.colorsRedColor;
            
            var redStart = yellowEnd;
            var redEnd   = this.startAngle + (this.endAngle - this.startAngle) * ((this.max - this.min) / (this.max - this.min))
    
            this.context.beginPath();
                this.context.arc(this.centerx, this.centery, this.radius - 10 - prop.borderWidth, redStart, redEnd, false);
                this.context.arc(this.centerx, this.centery, this.radius - (10 + prop.colorsRedWidth) - prop.borderWidth, redEnd, redStart, true);
            this.context.fill();
        };








        //
        // A placeholder function
        // 
        // @param object The event object
        //
        this.getShape = function (e) {};








        //
        // A getValue method
        // 
        // @param object e An event object
        //
        this.getValue = function (e)
        {
            var mouseXY = RGraph.getMouseXY(e);
            var mouseX  = mouseXY[0];
            var mouseY  = mouseXY[1];
    
            var angle = RGraph.getAngleByXY(this.centerx, this.centery, mouseX, mouseY);
    
            if (angle >= 0 && angle <= RGraph.HALFPI) {
                angle += RGraph.TWOPI;
            }
    
            var value = ((angle - this.startAngle) / (this.endAngle - this.startAngle)) * (this.max - this.min);
                value = value + this.min;
    
            if (value < this.min) {
                value = this.min
            }
    
            if (value > this.max) {
                value = this.max
            }
    
            return value;
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
    
            if (
                   mouseXY[0] > (this.centerx - this.radius)
                && mouseXY[0] < (this.centerx + this.radius)
                && mouseXY[1] > (this.centery - this.radius)
                && mouseXY[1] < (this.centery + this.radius)
                && RGraph.getHypLength(this.centerx, this.centery, mouseXY[0], mouseXY[1]) <= this.radius
                ) {
    
                return this;
            }
        };








        //
        // This draws the gradient that goes around the Gauge chart
        //
        this.drawGradient = function ()
        {
            if (prop.borderGradient) {
                
                this.context.beginPath();
        
                    var grad = this.context.createRadialGradient(this.centerx, this.centery, this.radius, this.centerx, this.centery, this.radius - 15);
                    grad.addColorStop(0, 'gray');
                    grad.addColorStop(1, 'white');
        
                    this.context.fillStyle = grad;
                    this.context.arc(this.centerx, this.centery, this.radius, 0, RGraph.TWOPI, false)
                    this.context.arc(this.centerx, this.centery, this.radius - 15, RGraph.TWOPI,0, true)
                this.context.fill();
            }
        };








        //
        // This method handles the adjusting calculation for when the mouse is moved
        // 
        // @param object e The event object
        //
        this.adjusting_mousemove = function (e)
        {
            //
            // Handle adjusting for the Bar
            //
            if (prop.adjustable && RGraph.Registry.get('adjusting') && RGraph.Registry.get('adjusting').uid == this.uid) {
                this.value = this.getValue(e);
                //RGraph.clear(this.canvas);
                RGraph.redrawCanvas(this.canvas);
                RGraph.fireCustomEvent(this, 'onadjust');
            }
        };








        //
        // This method returns an appropriate angle for the given value (in RADIANS)
        // 
        // @param number value The value to get the angle for
        //
        this.getAngle = function (value)
        {
            // Higher than max
            if (value > this.max || value < this.min) {
                return null;
            }
    
            //var value = ((angle - this.startAngle) / (this.endAngle - this.startAngle)) * (this.max - this.min);
                //value = value + this.min;
    
            var angle = (((value - this.min) / (this.max - this.min)) * (this.endAngle - this.startAngle)) + this.startAngle;
            
            return angle;
        };








        //
        // This allows for easy specification of gradients. Could optimise this not to repeatedly call parseSingleColors()
        //
        this.parseColors = function ()
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (this.original_colors.length === 0) {
                this.original_colors.backgroundColor   = RGraph.arrayClone(prop.backgroundColor);
                this.original_colors.colorsRedColor    = RGraph.arrayClone(prop.colorsRedColor);
                this.original_colors.colorsYellowColor = RGraph.arrayClone(prop.colorsYellowColor);
                this.original_colors.colorsGreenColor  = RGraph.arrayClone(prop.colorsGreenColor);
                this.original_colors.borderInner       = RGraph.arrayClone(prop.borderInner);
                this.original_colors.borderOuter       = RGraph.arrayClone(prop.borderOuter);
                this.original_colors.colorsRanges      = RGraph.arrayClone(prop.colorsRanges);
                this.original_colors.needleColors      = RGraph.arrayClone(prop.needleColors);
            }

            prop.backgroundColor   = this.parseSingleColorForGradient(prop.backgroundColor);
            prop.colorsRedColor    = this.parseSingleColorForGradient(prop.colorsRedColor);
            prop.colorsYellowColor = this.parseSingleColorForGradient(prop.colorsYellowColor);
            prop.colorsGreenColor  = this.parseSingleColorForGradient(prop.colorsGreenColor);
            prop.borderInner       = this.parseSingleColorForGradient(prop.borderInner);
            prop.borderOuter       = this.parseSingleColorForGradient(prop.borderOuter);
            
            // Parse the colorRanges value
            if (prop.colorsRanges) {
                
                var ranges = prop.colorsRanges;
    
                for (var i=0; i<ranges.length; ++i) {
                    ranges[i][2] = this.parseSingleColorForGradient(ranges[i][2], this.radius - 30);
                }
            }
    
            // Parse the needleColors value
            if (prop.needleColors) {
                
                var colors = prop.needleColors;
    
                for (var i=0; i<colors.length; ++i) {
                    colors[i] = this.parseSingleColorForGradient(colors[i]);
                }
            }
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
        // @param string color    The color to look for a gradient in
        // @param radius OPTIONAL The start radius to start the gradient at.
        //                        If not suppllied the centerx value is used
        //
        this.parseSingleColorForGradient = function (color)
        {
            var radiusStart = arguments[1] || 0;

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
                var grad = this.context.createRadialGradient(
                    this.centerx,
                    this.centery,
                    radiusStart,
                    this.centerx,
                    this.centery,
                    this.radius
                );
    
                var diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RGraph.trim(parts[0]));
    
                for (var j=1; j<parts.length; ++j) {
                    grad.addColorStop(j * diff, RGraph.trim(parts[j]));
                }
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
        // This function runs once only
        // (put at the end of the file (before any effects))
        //
        this.firstDrawFunc = function ()
        {
        };








        //
        // Gauge Grow
        // 
        // This effect gradually increases the represented value
        // 
        // @param object       Options for the effect. You can pass frames here
        // @param function     An optional callback function
        //
        this.grow = function ()
        {
            var obj      = this,
                opt      = arguments[0] ? arguments[0] : {},
                callback = arguments[1] ? arguments[1] : function () {},
                frames   = opt.frames || 30,
                frame    = 0;

            // Don't want any strings
            if (typeof this.value === 'string') {
                this.value = RGraph.stringsToNumbers(this.value);
            }

            // Single pointer
            if (typeof this.value === 'number') {
    
                var origValue = Number(this.currentValue);
    
                if (this.currentValue == null) {
                    this.currentValue = this.min;
                    origValue = this.min;
                }
    
                var newValue  = this.value,
                    diff      = newValue - origValue;
    
    

                var iterator = function ()
                {
                    obj.value = ((frame / frames) * diff) + origValue;

                    if (obj.value > obj.max) obj.value = obj.max;
                    if (obj.value < obj.min) obj.value = obj.min;
        
                    //RGraph.clear(obj.canvas);
                    RGraph.redrawCanvas(obj.canvas);
        
                    if (frame++ < frames) {
                        RGraph.Effects.updateCanvas(iterator);
                    } else {
                        callback(obj);
                    }
                };
    
                iterator();



            //
            // Multiple pointers
            //
            } else {

                if (this.currentValue == null) {
                    this.currentValue = [];
                    
                    for (var i=0; i<this.value.length; ++i) {
                        this.currentValue[i] = this.min;
                    }
                    
                    origValue = RGraph.arrayClone(this.currentValue);
                }

                var origValue = RGraph.arrayClone(this.currentValue);
                var newValue  = RGraph.arrayClone(this.value);
                var diff      = [];

                for (var i=0,len=newValue.length; i<len; ++i) {
                    diff[i] = newValue[i] - Number(this.currentValue[i]);
                }



                var iterator = function ()
                {
                    frame++;

                    for (var i=0,len=obj.value.length; i<len; ++i) {

                        obj.value[i] = ((frame / frames) * diff[i]) + origValue[i];

                        if (obj.value[i] > obj.max) obj.value[i] = obj.max;
                        if (obj.value[i] < obj.min) obj.value[i] = obj.min;
                    }

                    //RGraph.clear(obj.canvas);
                    RGraph.redrawCanvas(obj.canvas);


                    if (frame < frames) {
                        RGraph.Effects.updateCanvas(iterator);
                    } else {
                        callback(obj);
                    }
                };
        
                iterator();
            }
            
            return this;
        };








        //
        // Register the object
        //
        RGraph.register(this);








        //
        // This is the 'end' of the constructor so if the first argument
        // contains configuration data - handle that.
        //
        RGraph.parseObjectStyleConfig(this, conf.options);
    };