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


    RGraph = window.RGraph || {isrgraph:true,isRGraph: true,rgraph:true};
    RGraph.SVG = RGraph.SVG || {};

// Module pattern
(function (win, doc, undefined)
{
    RGraph.SVG.Waterfall = function (conf)
    {
        //
        // A setter that the constructor uses (at the end)
        // to set all of the properties
        //
        // @param string name  The name of the property to set
        // @param string value The value to set the property to
        //
        this.set = function (name, value)
        {
            if (arguments.length === 1 && typeof name === 'object') {
                for (i in arguments[0]) {
                    if (typeof i === 'string') {
                        
                        name  = ret.name;
                        value = ret.value;

                        this.set(name, value);
                    }
                }
            } else {
                var ret = RGraph.SVG.commonSetter({
                    object: this,
                    name:   name,
                    value:  value
                });
                
                name  = ret.name;
                value = ret.value;

                this.properties[name] = value;

                // If setting the colors, update the originalColors
                // property too
                if (name === 'colors') {
                    this.originalColors = RGraph.SVG.arrayClone(value);
                    this.colorsParsed = false;
                }
            }

            return this;
        };








        //
        // A getter.
        // 
        // @param name  string The name of the property to get
        //
        this.get = function (name)
        {
            return this.properties[name];
        };








        this.id               = conf.id;
        this.uid              = RGraph.SVG.createUID();
        this.container        = document.getElementById(this.id);
        this.layers          = {}; // MUST be before the SVG tag is created!
        this.svg              = RGraph.SVG.createSVG({object: this,container: this.container});
        this.isRGraph        = true;
        this.isrgraph        = true;
        this.rgraph          = true;
        this.data             = conf.data;
        this.type             = 'waterfall';
        this.coords           = [];
        this.colorsParsed     = false;
        this.originalColors   = {};
        this.gradientCounter  = 1;
        this.totalColumns     = [];






        // Add this object to the ObjectRegistry
        RGraph.SVG.OR.add(this);
        
        this.container.style.display = 'inline-block';
        
        //
        // Note the indexes of total columns
        //
        for (var i=0; i<this.data.length; ++i) {
            if (RGraph.SVG.isNull(this.data[i])) {
                this.totalColumns[i] = true;
            }
        }

        this.properties =
        {
            marginLeft:   35,
            marginRight:  35,
            marginTop:    35,
            marginBottom: 35,
            marginInner:  5,

            backgroundColor:            null,
            backgroundImage:            null,
            backgroundImageAspect:      'none',
            backgroundImageStretch:     true,
            backgroundImageOpacity:     null,
            backgroundImageX:           null,
            backgroundImageY:           null,
            backgroundImageW:           null,
            backgroundImageH:           null,
            backgroundGrid:             true,
            backgroundGridColor:        '#ddd',
            backgroundGridLinewidth:    1,
            backgroundGridHlines:       true,
            backgroundGridHlinesCount:  null,
            backgroundGridVlines:       true,
            backgroundGridVlinesCount:  null,
            backgroundGridBorder:       true,
            backgroundGridDashed:       false,
            backgroundGridDotted:       false,
            backgroundGridDashArray:    null,
            
            // 20 colors. If you need more you need to set the colors property
            colors:               ['black', 'red', 'blue'],
            colorsSequential:     false,
            colorsStroke:          '#aaa',
            colorsConnector: null,
            
            total:                true,
            linewidth:            1,

            yaxis:                true,
            yaxisTickmarks:       true,
            yaxisTickmarksLength: 5,
            yaxisColor:           'black',
            yaxisScale:           true,
            yaxisLabels:          null,
            yaxisLabelsOffsetx:   0,
            yaxisLabelsOffsety:   0,
            yaxisLabelsCount:     5,
            yaxisScaleUnitsPre:        '',
            yaxisScaleUnitsPost:       '',
            yaxisScaleStrict:          false,
            yaxisScaleDecimals:        0,
            yaxisScalePoint:           '.',
            yaxisScaleThousand:        ',',
            yaxisScaleRound:           false,
            yaxisScaleMax:             null,
            yaxisScaleMin:             0,
            yaxisScaleFormatter:       null,
            yaxisLabelsColor:       null,
            yaxisLabelsBold:        null,
            yaxisLabelsItalic:      null,
            yaxisLabelsFont:        null,
            yaxisLabelsSize:        null,

            xaxis:                true,
            xaxisTickmarks:       true,
            xaxisTickmarksLength: 5,
            xaxisLabels:          null,
            xaxisLabelsFont:      null,
            xaxisLabelsSize:      null,
            xaxisLabelsColor:     null,
            xaxisLabelsBold:      null,
            xaxisLabelsItalic:    null,
            xaxisLabelsPosition:  'section',
            xaxisLabelsPositionEdgeTickmarksCount: null,
            xaxisColor:           'black',
            xaxisLabelsOffsetx:   0,
            xaxisLabelsOffsety:   0,
            
            labelsAbove:                  false,
            labelsAboveFont:              null,
            labelsAboveSize:              null,
            labelsAboveBold:              null,
            labelsAboveItalic:            null,
            labelsAboveColor:             null,
            labelsAboveBackground:        'rgba(255,255,255,0.5)',
            labelsAboveBackgroundPadding: 2,
            labelsAboveUnitsPre:          null,
            labelsAboveUnitsPost:         null,
            labelsAbovePoint:             null,
            labelsAboveThousand:          null,
            labelsAboveFormatter:         null,
            labelsAboveDecimals:          null,
            labelsAboveOffsetx:           0,
            labelsAboveOffsety:           0,
            labelsAboveHalign:            'center',
            labelsAboveValign:            'bottom',
            labelsAboveSpecific:          null,
            labelsAboveLastFont:              null,
            labelsAboveLastBold:              null,
            labelsAboveLastItalic:            null,
            labelsAboveLastSize:              null,
            labelsAboveLastColor:             null,
            labelsAboveLastBackground:        null,
            labelsAboveLastBackgroundPadding: null,

            textColor:            'black',
            textFont:             'Arial, Verdana, sans-serif',
            textSize:             12,
            textBold:             false,
            textItalic:           false,

            
            tooltips:                        null,
            tooltipsOverride:                null,
            tooltipsEffect:                  'fade',
            tooltipsCssClass:                'RGraph_tooltip',
            tooltipsCss:                     null,
            tooltipsEvent:                   'click',
            tooltipsFormattedThousand:       ',',
            tooltipsFormattedPoint:          '.',
            tooltipsFormattedDecimals:       0,
            tooltipsFormattedUnitsPre:       '',
            tooltipsFormattedUnitsPost:      '',
            tooltipsFormattedKeyColors:      null,
            tooltipsFormattedKeyColorsShape: 'square',
            tooltipsFormattedKeyLabels:      [],

            highlightStroke:      'rgba(0,0,0,0)',
            highlightFill:        'rgba(255,255,255,0.7)',
            highlightLinewidth:   1,
            
            title:                '',
            titleX:               null,
            titleY:               null,
            titleHalign:          'center',
            titleValign:          null,
            titleSize:            null,
            titleColor:           null,
            titleFont:            null,
            titleBold:            null,
            titleItalic:          null,

            titleSubtitle:        null,
            titleSubtitleSize:    null,
            titleSubtitleColor:   '#aaa',
            titleSubtitleFont:    null,
            titleSubtitleBold:    null,
            titleSubtitleItalic:  null,
            
            //shadow:               false,
            //shadowOffsetx:        2,
            //shadowOffsety:        2,
            //shadowBlur:           2,
            //shadowOpacity:        0.25,

            key:            null,
            keyColors:      null,
            keyOffsetx:     0,
            keyOffsety:     0,
            keyLabelsOffsetx: 0,
            keyLabelsOffsety: -1,
            keyLabelsFont:    null,
            keyLabelsSize:    null,
            keyLabelsColor:   null,
            keyLabelsBold:    null,
            keyLabelsItalic:  null
        };




        //
        // Copy the global object properties to this instance
        //
        RGraph.SVG.getGlobals(this);





        //
        // "Decorate" the object with the generic effects if the effects library has been included
        //
        if (RGraph.SVG.FX && typeof RGraph.SVG.FX.decorate === 'function') {
            RGraph.SVG.FX.decorate(this);
        }





        // Add the responsive function to the object
        this.responsive = RGraph.SVG.responsive;




        var prop = this.properties;








        //
        // The draw method draws the Bar chart
        //
        this.draw = function ()
        {
            // Fire the beforedraw event
            RGraph.SVG.fireCustomEvent(this, 'onbeforedraw');



            // Should the first thing that's done inthe.draw() function
            // except for the onbeforedraw event
            this.width  = Number(this.svg.getAttribute('width'));
            this.height = Number(this.svg.getAttribute('height'));



            // Create the defs tag if necessary
            RGraph.SVG.createDefs(this);




            this.coords      = []; // Reset this so it doesn't grow
            this.graphWidth  = this.width - prop.marginLeft - prop.marginRight;
            this.graphHeight = this.height - prop.marginTop - prop.marginBottom;



            // Parse the colors for gradients
            RGraph.SVG.resetColorsToOriginalValues({object:this});
            this.parseColors();
            
            
            
            
            // Work out the sum of the data and add it to the data
            if (prop.total && !this.totalAdded) {

                this.totalAdded = true;
            
                var sum = RGraph.SVG.arraySum(this.data);
                
                // Now append the sum to the data
                this.data.push(sum);
                
                // May need to append something to the labels array if prop.total
                // is enabled, so that the labels line up

                if (prop.xaxisLabels && prop.xaxisLabels.length === (this.data.length - 1)) {
                    prop.xaxisLabels.push('');
                }
            }




            for (var i=0,max=0,runningTotal=0; i<this.data.length - (prop.total ? 1 : 0); ++i) {
                runningTotal += this.data[i]
                max = Math.max(Math.abs(max), Math.abs(runningTotal));
            }

            // A custom, user-specified maximum value
            if (typeof prop.yaxisScaleMax === 'number') {
                max = prop.yaxisScaleMax;
            }
            
            // Set the ymin to zero if it's set mirror
            if (prop.yaxisScaleMin === 'mirror' || prop.yaxisScaleMin === 'middle' || prop.yaxisScaleMin === 'center') {
                var mirrorScale = true;
                prop.yaxisScaleMin   = 0;
            }


            //
            // Generate an appropiate scale
            //
            this.scale = RGraph.SVG.getScale({
                object:    this,
                numlabels: prop.yaxisLabelsCount,
                unitsPre:  prop.yaxisScaleUnitsPre,
                unitsPost: prop.yaxisScaleUnitsPost,
                max:       max,
                min:       prop.yaxisScaleMin,
                point:     prop.yaxisScalePoint,
                round:     prop.yaxisScaleRound,
                thousand:  prop.yaxisScaleThousand,
                decimals:  prop.yaxisScaleDecimals,
                strict:    typeof prop.yaxisScaleMax === 'number',
                formatter: prop.yaxisScaleFormatter
            });
                


            //
            // Get the scale a second time if the ymin should be mirored
            //
            // Set the ymin to zero if it's set mirror
            if (mirrorScale) {
                this.scale = RGraph.SVG.getScale({
                    object: this,
                    numlabels: prop.yaxisLabelsCount,
                    unitsPre:  prop.yaxisScaleUnitsPre,
                    unitsPost: prop.yaxisScaleUnitsPost,
                    max:       this.scale.max,
                    min:       this.scale.max * -1,
                    point:     prop.yaxisScalePoint,
                    round:     false,
                    thousand:  prop.yaxisScaleThousand,
                    decimals:  prop.yaxisScaleDecimals,
                    strict:    typeof prop.yaxisScaleMax === 'number',
                    formatter: prop.yaxisScaleFormatter
                });
            }

            // Now the scale has been generated adopt its max value
            this.max      = this.scale.max;
            this.min      = this.scale.min;
            prop.yaxisScaleMax = this.scale.max;
            prop.yaxisScaleMin = this.scale.min;




            // Draw the background first
            RGraph.SVG.drawBackground(this);



            // Draw the axes BEFORE the bars
            RGraph.SVG.drawXAxis(this);
            RGraph.SVG.drawYAxis(this);


            // Draw the bars
            this.drawBars();
            
            
            // Draw the labelsAbove labels
            this.drawLabelsAbove();








            
            
            // Draw the key
            if (typeof prop.key !== null && RGraph.SVG.drawKey) {
                RGraph.SVG.drawKey(this);
            } else if (!RGraph.SVG.isNull(prop.key)) {
                alert('The drawKey() function does not exist - have you forgotten to include the key library?');
            }











            // Add the event listener that clears the highlight rect if
            // there is any. Must be MOUSEDOWN (ie before the click event)
            //var obj = this;
            //document.body.addEventListener('mousedown', function (e)
            //{
            //    //RGraph.SVG.removeHighlight(obj);
            //
            //}, false);



            // Fire the draw event
            RGraph.SVG.fireCustomEvent(this, 'ondraw');




            return this;
        };








        //
        // Draws the bars
        //
        this.drawBars = function ()
        {
            this.graphWidth  = this.width  - prop.marginLeft - prop.marginRight;
            this.graphHeight = this.height - prop.marginTop - prop.marginBottom;
            
            // The width of the bars
            var innerWidth = (this.graphWidth / this.data.length) - (2 * prop.marginInner),
                outerWidth = (this.graphWidth / this.data.length);


            // The starting Y coordinate
            var y     = this.getYCoord(0),
                total = 0;



            // Loop thru the data drawing the bars
            for (var i=0; i<(this.data.length); ++i) {
            
                var prevValue    = this.data[i - 1],
                    nextValue    = this.data[i + 1],
                    currentValue = this.data[i],
                    prevTotal    = total;

                total += parseFloat(this.data[i]) || 0;

                // Figure out the height
                var height = Math.abs((this.data[i] / (this.scale.max - this.scale.min) ) * this.graphHeight);










                // Work out the starting coord
                if (RGraph.SVG.isNull(prevValue)) {
                    
                    if (currentValue > 0) {
                        y = this.getYCoord(prevTotal) - height;
                    } else {
                        y = this.getYCoord(prevTotal);
                    }

                } else {
                    if (i == 0 && this.data[i] > 0) {
                        y = y - height;
    
                    } else if (this.data[i] > 0 && this.data[i - 1] > 0) {
                        y = y - height;
    
                    } else if (this.data[i] > 0 && this.data[i - 1] < 0) {
                        y = y + prevHeight - height;
    
                    } else if (this.data[i] < 0 && this.data[i - 1] > 0) {
                        // Nada
    
                    } else if (this.data[i] < 0 && this.data[i - 1] < 0) {
                        y = y + prevHeight;
                    }
                }
                
                //
                // Determine the color
                //
                var fill = this.data[i] > 0 ? prop.colors[0] : prop.colors[1];
                
                if (prop.colorsSequential) {
                    fill = prop.colors[i];
                }

                
                
                
                
                // The last (the total) value if required
                if (prop.total) {
                    if (i === (this.data.length - 1) && this.data[this.data.length - 1] >= 0) {
                        
                        y = this.getYCoord(0) - height;
    
                        if (!prop.colorsSequential) {
                            fill = prop.colors[2];
                        }
                    } else if (i === (this.data.length - 1) && this.data[this.data.length - 1] < 0) {
                        y    = this.getYCoord(0);
    
                        if (!prop.colorsSequential) {
                            fill = prop.colors[2];
                        }
                    }
                }





                // Calculate the X coordinate
                var x = prop.marginLeft + (outerWidth * i) + prop.marginInner;





                // This handles an intermediate total
                if (this.data[i] === null || typeof this.data[i] === 'undefined') {
                    
                    var axisY = this.getYCoord(0);
                    
                    if (prevValue < 0) {
                        y = prevY + prevHeight;
                    } else {
                        y = prevY;
                    }

                    height = this.getYCoord(0) - this.getYCoord(total);
                    
                    // Do this if not sequential colors
                    if (!prop.colorsSequential) {
                        fill   = prop.colors[3] || prop.colors[2];
                    }
                    
                    if (height < 0) {
                        y += height;
                        height *= -1;
                    }
                }






                // Create the rect object
                var rect = RGraph.SVG.create({
                    svg: this.svg,
                    type: 'rect',
                    parent: this.svg.all,
                    attr: {
                        x: x,
                        y: y,
                        width: innerWidth,
                        height: height,
                        stroke: prop.colorsStroke,
                        fill: fill,
                        'stroke-width': prop.linewidth,
                        'shape-rendering': 'crispEdges',
                        'data-index': i,
                        'data-original-x': x,
                        'data-original-y': y,
                        'data-original-width': innerWidth,
                        'data-original-height': height,
                        'data-original-stroke': prop.colorsStroke,
                        'data-original-fill': fill,
                        'data-value': String(this.data[i])
                    }
                });
                
                // Store the coordinates
                this.coords.push({
                    object:  this,
                    element: rect,
                    x:       x,
                    y:       y,
                    width:   innerWidth,
                    height:  height
                });








                // Add the tooltips
                if (!RGraph.SVG.isNull(prop.tooltips) && (prop.tooltips[i] || typeof prop.tooltips === 'string') ) {

                    var obj = this;

                    //
                    // Add tooltip event listeners
                    //
                    (function (idx)
                    {
                        rect.addEventListener(prop.tooltipsEvent.replace(/^on/, ''), function (e)
                        {
                            obj.removeHighlight();

                            // Show the tooltip
                            RGraph.SVG.tooltip({
                                object:  obj,
                                 index:  idx,
                                 group:  0,
                       sequentialIndex:  idx,
                                  text:  typeof prop.tooltips === 'string' ? prop.tooltips : prop.tooltips[idx],
                                 event:  e
                            });
                            
                            // Highlight the rect that has been clicked on
                            obj.highlight(e.target);
                        }, false);

                        rect.addEventListener('mousemove', function (e)
                        {
                            e.target.style.cursor = 'pointer'
                        }, false);
                    })(i);
                }










                // Store these for the next iteration of the loop
                var prevX      = x,
                    prevY      = y,
                    prevWidth  = innerWidth,
                    prevHeight = height,
                    prevValue  = this.data[i];
            }




















            // Now draw the connecting lines
            for (var i=0; i<this.coords.length; ++i) {

                if (this.coords[i+1] && this.coords[i+1].element) {
                    
                    var x1 = Number(this.coords[i].element.getAttribute('x')) + Number(this.coords[i].element.getAttribute('width')),
                        y1 = parseInt(this.coords[i].element.getAttribute('y')) +          (this.data[i] > 0 ? 0 : parseInt(this.coords[i].element.getAttribute('height')) ),
                        x2 = x1 + (2 * prop.marginInner),
                        y2 = parseInt(this.coords[i].element.getAttribute('y')) +          (this.data[i] > 0 ? 0 : parseInt(this.coords[i].element.getAttribute('height')) );

                    // Handle total columns
                    if(this.coords[i].element.getAttribute('data-value') === 'null') {
                        if (i === (this.data.length - 1) ) {
                            y1 = parseFloat(this.coords[i].element.getAttribute('y'));
                            y2 = parseFloat(y1);
                        }

                        if (this.totalColumns[i]) {
                            // Calculate the total thus far
                            for (var k=0,total=0; k<i; ++k) {
                                total += this.data[k];
                            }
                        
                            if (total > 0 && this.data[i-1] > 0) {
                                y1 = Number(this.coords[i-1].element.getAttribute('y'));
                                y2 = y1;
                            } else if (total > 0 && this.data[i-1] < 0) {
                                y1 = Number(this.coords[i-1].element.getAttribute('y')) + Number(this.coords[i-1].element.getAttribute('height'));
                                y2 = y1;
                            }
                        }
                    }


                    var line = RGraph.SVG.create({
                        svg: this.svg,
                        type: 'line',
                        parent: this.svg.all,
                        attr: {
                            x1: x1,
                            y1: y1 + 0.5,
                            x2: x2,
                            y2: y2 + 0.5,
                            stroke: prop.colorsConnector || prop.colorsStroke,
                            'stroke-width': prop.linewidth,
                            'data-index': i,
                            'data-original-x1': x1,
                            'data-original-y1': y1 + 0.5,
                            'data-original-x2': x2,
                            'data-original-y2': y2 + 0.5
                        }
                    });

                }
            }
        };








        //
        // This function can be used to retrieve the relevant Y coordinate for a
        // particular value.
        // 
        // @param int value The value to get the Y coordinate for
        //
        this.getYCoord = function (value)
        {
            var prop = this.properties;

            if (value > this.scale.max) {
                return null;
            }

            var y, xaxispos = prop.xaxispos;

            if (value < this.scale.min) {
                return null;
            }

            y  = ((value - this.scale.min) / (this.scale.max - this.scale.min));
            y *= (this.height - prop.marginTop - prop.marginBottom);

            y = this.height - prop.marginBottom - y;

            return y;
        };








        //
        // This function can be used to highlight a bar on the chart
        // 
        // @param object rect The rectangle to highlight
        //
        this.highlight = function (rect)
        {
            var x      = rect.getAttribute('x'),
                y      = rect.getAttribute('y'),
                width  = rect.getAttribute('width'),
                height = rect.getAttribute('height');
            
            var highlight = RGraph.SVG.create({
                svg: this.svg,
                type: 'rect',
                parent: this.svg.all,
                attr: {
                    stroke: prop.highlightStroke,
                    fill: prop.highlightFill,
                    x: x,
                    y: y,
                    width: width,
                    height: height,
                    'stroke-width': prop.highlightLinewidth
                },
                style: {
                    pointerEvents: 'none'
                }
            });


            //if (prop.tooltipsEvent === 'mousemove') {
                
                //var obj = this;
                
                //highlight.addEventListener('mouseout', function (e)
                //{
                //    obj.removeHighlight();
                //    RGraph.SVG.hideTooltip();
                //    RGraph.SVG.REG.set('highlight', null);
                //}, false);
            //}


            // Store the highlight rect in the rebistry so
            // it can be cleared later
            RGraph.SVG.REG.set('highlight', highlight);
        };








        //
        // This allows for easy specification of gradients
        //
        this.parseColors = function () 
        {
            // Save the original colors so that they can be restored when
            // the canvas is cleared
            if (!Object.keys(this.originalColors).length) {
                this.originalColors = {
                    colors:              RGraph.SVG.arrayClone(prop.colors),
                    backgroundGridColor: RGraph.SVG.arrayClone(prop.backgroundGridColor),
                    highlightFill:       RGraph.SVG.arrayClone(prop.highlightFill),
                    backgroundColor:     RGraph.SVG.arrayClone(prop.backgroundColor)
                }
            }

            
            // colors
            var colors = prop.colors;

            if (colors) {
                for (var i=0; i<colors.length; ++i) {
                    colors[i] = RGraph.SVG.parseColorLinear({
                        object: this,
                        color: colors[i]
                    });
                }
            }

            prop.backgroundGridColor = RGraph.SVG.parseColorLinear({object: this, color: prop.backgroundGridColor});
            prop.highlightFill       = RGraph.SVG.parseColorLinear({object: this, color: prop.highlightFill});
            prop.backgroundColor     = RGraph.SVG.parseColorLinear({object: this, color: prop.backgroundColor});
        };








        //
        // Draws the labelsAbove
        //
        this.drawLabelsAbove = function ()
        {
            // Go through the above labels
            if (prop.labelsAbove) {
            
                var total = 0;

                for (var i=0; i<this.coords.length; ++i) {
                    
                    var num    = this.data[i],
                        total  = total + num;

                    if (typeof num === 'number' || RGraph.SVG.isNull(num)) {
                        
                        if (RGraph.SVG.isNull(num)) {
                            num = total;
                        }

                        var str = RGraph.SVG.numberFormat({
                            object:    this,
                            num:       num.toFixed(prop.labelsAboveDecimals),
                            prepend:   typeof prop.labelsAboveUnitsPre  === 'string'   ? prop.labelsAboveUnitsPre  : null,
                            append:    typeof prop.labelsAboveUnitsPost === 'string'   ? prop.labelsAboveUnitsPost : null,
                            point:     typeof prop.labelsAbovePoint     === 'string'   ? prop.labelsAbovePoint     : null,
                            thousand:  typeof prop.labelsAboveThousand  === 'string'   ? prop.labelsAboveThousand  : null,
                            formatter: typeof prop.labelsAboveFormatter === 'function' ? prop.labelsAboveFormatter : null
                        });

                        // Facilitate labelsAboveSpecific
                        if (prop.labelsAboveSpecific && prop.labelsAboveSpecific.length && (typeof prop.labelsAboveSpecific[i] === 'string' || typeof prop.labelsAboveSpecific[i] === 'number') ) {
                            str = prop.labelsAboveSpecific[i];
                        } else if ( prop.labelsAboveSpecific && prop.labelsAboveSpecific.length && typeof prop.labelsAboveSpecific[i] !== 'string' && typeof prop.labelsAboveSpecific[i] !== 'number') {
                            continue;
                        }
    
                        var x = parseFloat(this.coords[i].element.getAttribute('x')) + parseFloat(this.coords[i].element.getAttribute('width') / 2) + prop.labelsAboveOffsetx;
    
                        if (this.data[i] >= 0) {
                            var y = parseFloat(this.coords[i].element.getAttribute('y')) - 7 + prop.labelsAboveOffsety;
                            var valign = prop.labelsAboveValign;
                        } else {
                            var y = parseFloat(this.coords[i].element.getAttribute('y')) + parseFloat(this.coords[i].element.getAttribute('height')) + 7 - prop.labelsAboveOffsety;
                            var valign = prop.labelsAboveValign === 'top' ? 'bottom' : 'top';
                        }









                        // Formatting options for the labels
                        //
                        // NB The last label can have different formatting
                        if (i === (this.coords.length - 1) ) {

                            var font       = prop.labelsAboveLastFont              || prop.labelsAboveFont              || prop.textFont,
                                color      = prop.labelsAboveLastColor             || prop.labelsAboveColor             || prop.textColor,
                                background = prop.labelsAboveLastBackground        || prop.labelsAboveBackground        || null,
                                padding    = (typeof prop.labelsAboveLastBackgroundPadding === 'number' ? prop.labelsAboveLastBackgroundPadding : prop.labelsAboveBackgroundPadding) || 0;
                            
                            // Size
                            if (typeof prop.labelsAboveLastSize === 'number') {
                                var size = prop.labelsAboveLastSize;
                            } else if (typeof prop.labelsAboveSize === 'number') {
                                var size = prop.labelsAboveSize;
                            } else {
                                var size = prop.textBold;
                            }

                            // Bold
                            if (typeof prop.labelsAboveLastBold === 'boolean') {
                                var bold = prop.labelsAboveLastBold;
                            } else if (typeof prop.labelsAboveBold === 'boolean') {
                                var bold = prop.labelsAboveBold;
                            } else {
                                var bold = prop.textBold;
                            }

                            // Italic
                            if (typeof prop.labelsAboveLastItalic === 'boolean') {
                                var italic = prop.labelsAboveLastItalic;
                            } else if (typeof prop.labelsAboveItalic === 'boolean') {
                                var italic = prop.labelsAboveItalic;
                            } else {
                                var italic = prop.textItalic;
                            }

                        
                        
                        
                        
                        
                        
                        
                        
                        
                        } else {
                            var font       = prop.labelsAboveFont              || prop.textFont,
                                size       = typeof prop.labelsAboveSize === 'number' ? prop.labelsAboveSize : prop.textSize,
                                color      = prop.labelsAboveColor             || prop.textColor,
                                background = prop.labelsAboveBackground        || null,
                                padding    = prop.labelsAboveBackgroundPadding || 0;


                            // Bold
                            if (typeof prop.labelsAboveBold === 'boolean') {
                                var bold = prop.labelsAboveBold;
                            } else {
                                var bold = prop.textBold;
                            }

                            // Italic
                            if (typeof prop.labelsAboveItalic === 'boolean') {
                                var italic = prop.labelsAboveItalic;
                            } else {
                                var italic = prop.textItalic;
                            }
                        }





                        RGraph.SVG.text({
                            object:     this,
                            parent:     this.svg.all,
                            tag:        'labels.above',
                            text:       str,
                            x:          x,
                            y:          y,
                            halign:     prop.labelsAboveHalign,
                            valign:     valign,
                            
                            font:       font,
                            size:       size,
                            bold:       bold,
                            italic:     italic,
                            color:      color,
                            
                            background: background,
                            padding:    padding
                        });
                    }
                }
            }
        };








        //
        // Using a function to add events makes it easier to facilitate method
        // chaining
        // 
        // @param string   type The type of even to add
        // @param function func 
        //
        this.on = function (type, func)
        {
            if (type.substr(0,2) !== 'on') {
                type = 'on' + type;
            }
            
            RGraph.SVG.addCustomEventListener(this, type, func);
    
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
        // Remove highlight from the chart (tooltips)
        //
        this.removeHighlight = function ()
        {
            var highlight = RGraph.SVG.REG.get('highlight');
            if (highlight && highlight.parentNode) {
                highlight.parentNode.removeChild(highlight);
            }
            
            RGraph.SVG.REG.set('highlight', null);
        };








        //
        // A worker function that handles Bar chart specific tooltip substitutions
        //
        this.tooltipSubstitutions = function (opt)
        {
            return {
                  index: opt.index,
                dataset: 0,
        sequentialIndex: opt.index,
                  value: this.data[opt.index],
                 values: [this.data[opt.index]]
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
            var color, label, value;

            //
            // Check for null values (ie subtotals) and calculate the subtotal if required
            //
        
            // Determine the correct color to use
            var colors = prop.colors;
        
            if (   prop.tooltipsFormattedKeyColors
                && prop.tooltipsFormattedKeyColors[0]
                && prop.tooltipsFormattedKeyColors[1]
                && prop.tooltipsFormattedKeyColors[2]) {
        
                colors = prop.tooltipsFormattedKeyColors;
            //} else {
            //    colors = prop.colors;
            }
            
            color  = colors[0];
        
            // Change the color for negative bars
            if (specific.value < 0) {
                color = colors[1];
            }
        
            // Change the color for the last bar
            if ( (specific.index + 1) === this.data.length || RGraph.SVG.isNull(this.data[specific.index])) {
                color = colors[2];
            }




            // Figure out the correct label
            if (prop.tooltipsFormattedKeyLabels && typeof prop.tooltipsFormattedKeyLabels === 'object') {
            
                var isLast      = specific.index === (this.data.length - 1);
                var isNull      = RGraph.SVG.isNull(this.data[specific.index]);
                var isPositive  = specific.value > 0;
                var isNegative  = specific.value < 0;

                if (isLast) {
                    label = typeof prop.tooltipsFormattedKeyLabels[2] === 'string' ? prop.tooltipsFormattedKeyLabels[2] : '';
                } else if (!isLast && isNull) {
                    label = typeof prop.tooltipsFormattedKeyLabels[3] === 'string' ? prop.tooltipsFormattedKeyLabels[3] : '';
                } else if (typeof prop.tooltipsFormattedKeyLabels[0] === 'string' && isPositive && !isLast) {
                    label = prop.tooltipsFormattedKeyLabels[0];
                } else if (typeof prop.tooltipsFormattedKeyLabels[1] === 'string' && isNegative) {
                    label = prop.tooltipsFormattedKeyLabels[1];
                } else if (typeof prop.tooltipsFormattedKeyLabels[2] === 'string' && isLast) {
                    label = prop.tooltipsFormattedKeyLabels[2];
                }
            }





            //
            // Calculate the subtotal for null values which are
            // within the dataset
            //
            if (RGraph.SVG.isNull(this.data[specific.index])) {
                
                // Calculate the total thus far
                for (var i=0,value=0; i<=specific.index; ++i) {
                    value += this.data[i];
                }
            }

            return {
                label: label,
                color: color,
                value: value
            };
        };








        //
        // Set the options that the user has provided
        //
        for (i in conf.options) {
            if (typeof i === 'string') {
                this.set(i, conf.options[i]);
            }
        }
    };
            
    return this;

// End module pattern
})(window, document);