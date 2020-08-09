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
    RGraph.Line = function (conf)
    {
        var id                  = conf.id;
        var canvas              = document.getElementById(id);
        var data                = conf.data;

        this.id                 = id;
        this.canvas             = canvas;
        this.context            = this.canvas.getContext('2d');
        this.canvas.__object__  = this;
        this.type               = 'line';
        this.max                = 0;
        this.coords             = [];
        this.coords2            = [];
        this.coords.key         = [];
        this.coordsText         = [];
        this.coordsSpline       = [];
        this.coordsAxes         = {xaxis: [], yaxis: []};
        this.hasnegativevalues  = false;
        this.isRGraph           = true;
        this.isrgraph           = true;
        this.rgraph             = true;
        this.uid                = RGraph.createUID();
        this.canvas.uid         = this.canvas.uid ? this.canvas.uid : RGraph.createUID();
        this.colorsParsed       = false;
        this.original_colors    = [];
        this.firstDraw          = true; // After the first draw this will be false

        // Various config type stuff
        this.properties =
        {
            backgroundBarsCount:        null,
            backgroundBarsColor1:       'rgba(0,0,0,0)',
            backgroundBarsColor2:       'rgba(0,0,0,0)',
            backgroundGrid:             1,
            backgroundGridLinewidth:    1,
            backgroundGridHsize:        25,
            backgroundGridVsize:        25,
            backgroundGridColor:        '#ddd',
            backgroundGridVlines:       true,
            backgroundGridHlines:       true,
            backgroundGridBorder:       true,
            backgroundGridAutofit:      true,
            backgroundGridAutofitAlign: true,
            backgroundGridHlinesCount:  5,
            backgroundGridVlinesCount:  null,
            backgroundGridDashed:       false,
            backgroundGridDotted:       false,
            backgroundHbars:            null,
            backgroundImage:            null,
            backgroundImageStretch:     true,
            backgroundImageX:           null,
            backgroundImageY:           null,
            backgroundImageW:           null,
            backgroundImageH:           null,
            backgroundImageAlign:       null,
            backgroundColor:            null,

            xaxis:                   true,
            xaxisLinewidth:          1,
            xaxisColor:              'black',
            xaxisTickmarks:          true,
            xaxisTickmarksLength:    3,
            xaxisTickmarksLastLeft:  null,
            xaxisTickmarksLastRight: null,
            xaxisTickmarksCount:  null,
            xaxisLabels:          null,            
            xaxisLabelsSize:      null,
            xaxisLabelsFont:      null,
            xaxisLabelsItalic:    null,
            xaxisLabelsBold:      null,
            xaxisLabelsColor:     null,
            xaxisLabelsOffsetx:   0,
            xaxisLabelsOffsety:   0,
            xaxisLabelsHalign:   null,
            xaxisLabelsValign:   null,
            xaxisLabelsPosition:  'edge',
            xaxisLabelsSpecificAlign:'left',
            xaxisPosition:        'bottom',
            xaxisPosition:        'bottom',
            xaxisLabelsAngle:     0,
            xaxisTitle:           '',
            xaxisTitleBold:       null,
            xaxisTitleSize:       null,
            xaxisTitleFont:       null,
            xaxisTitleColor:      null,
            xaxisTitleItalic:     null,
            xaxisTitlePos:        null,
            xaxisTitleOffsetx:    0,
            xaxisTitleOffsety:    0,
            xaxisTitleX:          null,
            xaxisTitleY:          null,
            xaxisTitleHalign:     'center',
            xaxisTitleValign:     'top',

            yaxis:                    true,
            yaxisLinewidth:           1,
            yaxisColor:               'black',
            yaxisTickmarks:           true,
            yaxisTickmarksCount:      null,
            yaxisTickmarksLastTop:    null,
            yaxisTickmarksLastBottom: null,
            yaxisTickmarksLength:     3,
            yaxisScale:               true,
            yaxisScaleMin:            0,
            yaxisScaleMax:            null,
            yaxisScaleUnitsPre:       '',
            yaxisScaleUnitsPost:      '',
            yaxisScaleDecimals:       0,
            yaxisScalePoint:          '.',
            yaxisScaleThousand:       ',',
            yaxisScaleRound:          false,
            yaxisScaleFormatter:      null,
            yaxisScaleInvert:         false,
            yaxisLabelsSpecific:      null,
            yaxisLabelsCount:         5,
            yaxisLabelsOffsetx:       0,
            yaxisLabelsOffsety:       0,
            yaxisLabelsHalign:        null,
            yaxisLabelsValign:        null,
            yaxisLabelsFont:          null,
            yaxisLabelsSize:          null,
            yaxisLabelsColor:         null,
            yaxisLabelsBold:          null,
            yaxisLabelsItalic:        null,
            yaxisLabelsPosition:      'edge',
            yaxisPosition:            'left',
            yaxisTitle:               '',
            yaxisTitleBold:           null,
            yaxisTitleSize:           null,
            yaxisTitleFont:           null,
            yaxisTitleColor:          null,
            yaxisTitleItalic:         null,
            yaxisTitlePos:            null,
            yaxisTitleX:              null,
            yaxisTitleY:              null,
            yaxisTitleOffsetx:        0,
            yaxisTitleOffsety:        0,
            yaxisTitleHalign:         null,
            yaxisTitleValign:         null,
            yaxisTitleAccessible:     null,
            
            labelsAbove:                false,
            labelsAboveDecimals:        null,
            labelsAboveSize:            null,
            labelsAboveColor:           null,
            labelsAboveFont:            null,
            labelsAboveBold:            null,
            labelsAboveItalic:          null,
            labelsAboveBackground:      'rgba(255,255,255,0.7)',
            labelsAboveBorder:          false,
            labelsAboveOffsety:         5,
            labelsAboveUnitsPre:        '',
            labelsAboveUnitsPost:       '',
            labelsAboveSpecific:        null,

            linewidth:                  2.001,

            colors:                     ['red', '#0f0', '#00f', '#f0f', '#ff0', '#0ff','green','pink','blue','black'],
            
            tickmarksStyle:             'none',
            tickmarksLinewidth:         null,
            tickmarksSize:              3,
            tickmarksStyleDotStroke:    'white',
            tickmarksStyleDotFill:      null,
            tickmarksStyleDotLinewidth: 3,
            tickmarksStyleImage:        null,
            tickmarksStyleImageHalign:  'center',
            tickmarksStyleImageValign:  'center',
            tickmarksStyleImageOffsetx: 0,
            tickmarksStyleImageOffsety: 0,

            marginLeft:                 35,
            marginRight:                35,
            marginTop:                  35,
            marginBottom:               35,
            marginInner:                0,
            
            filledColors:               null,
            
            textBold:                   false,
            textItalic:                 false,
            textSize:                   12,
            textColor:                  'black',
            textFont:                   'Arial, Verdana, sans-serif',
            textAccessible:             true,
            textAccessibleOverflow:     'visible',
            textAccessiblePointerevents:false,
            
            title:                      '',
            titleBackground:            null,
            titleHpos:                  null,
            titleVpos:                  null,
            titleFont:                  null,
            titleSize:                  null,
            titleColor:                 null,
            titleBold:                  null,
            titleItalic:                null,
            titleX:                     null,
            titleY:                     null,
            titleHalign:                null,
            titleValign:                null,
            
            shadow:                     true,
            shadowOffsetx:              2,
            shadowOffsety:              2,
            shadowBlur:                 3,
            shadowColor:                'rgba(128,128,128,0.5)',
            
            tooltips:                   null,
            tooltipsHotspotXonly:       false,
            tooltipsHotspotSize:        5,
            tooltipsEffect:             'fade',
            tooltipsCssClass:           'RGraph_tooltip',
            tooltipsCss:                null,
            tooltipsEvent:              'onmousemove',
            tooltipsHighlight:          true,
            tooltipsCoordsPage:         false,
            tooltipsFormattedThousand:  ',',
            tooltipsFormattedPoint:     '.',
            tooltipsFormattedDecimals:  0,
            tooltipsFormattedUnitsPre:  '',
            tooltipsFormattedUnitsPost: '',
            tooltipsFormattedKeyColors: null,
            tooltipsFormattedKeyColorsShape: 'square',
            tooltipsFormattedKeyLabels: [],
            tooltipsDataset:            null,
            tooltipsDatasetEvent:       'click',

            highlightStyle:             null,
            highlightStroke:            'gray',
            highlightFill:              'white',
            highlightPointRadius:       2,
            
            stepped:                    false,
            
            key:                        null,
            keyBackground:              'white',
            keyPosition:                'graph',
            keyHalign:                  null,
            keyShadow:                  false,
            keyShadowColor:             '#666',
            keyShadowBlur:              3,
            keyShadowOffsetx:           2,
            keyShadowOffsety:           2,
            keyPositionMarginBoxed:     false,
            keyPositionX:               null,
            keyPositionY:               null,
            keyColorShape:              'square',
            keyRounded:                 true,
            keyLinewidth:               1,
            keyColors:                  null,
            keyInteractive:             false,
            keyInteractiveHighlightChartStroke: 'rgba(255,0,0,0.3)',
            keyInteractiveHighlightLabel: 'rgba(255,0,0,0.2)',
            keyLabelsFont:              null,
            keyLabelsSize:              null,
            keyLabelsColor:             null,
            keyLabelsBold:              null,
            keyLabelsItalic:            null,
            keyLabelsOffsetx:           0,
            keyLabelsOffsety:           0,

            contextmenu:                null,

            crosshairs:                 false,
            crosshairsColor:            '#333',
            crosshairsHline:            true,
            crosshairsVline:            true,
            
            annotatable:                false,
            annotatableColor:           'black',
            annotatableLinewidth:       1,

            filled:                     false,
            filledRange:                false,
            filledRangeThreshold:       null,
            filledRangeThresholdColors: ['red', 'green'],
            filledAccumulative:    		true,

            variant:                	null,

            axesAbove:              	false,

            backdrop:               	false,
            backdropSize:          		30,
            backdropAlpha:         		0.2,

            adjustable:             	false,
            adjustableOnly:        		null,

            redraw:                     true,

            outofbounds:                false,
            outofboundsClip:            false,

            animationFactor:            1,
            animationUnfoldX:           false,
            animationUnfoldY:           true,
            animationUnfoldInitial:     2,
            animationTraceClip:         1,
            animationTraceCenter:       false,

            spline:                     false,

            lineVisible:                [],

            errorbars:                  false,
            errorbarsColor:             'black',
            errorbarsCapped:            true,
            errorbarsCappedWidth:       12,
            errorbarsLinewidth:         1,

            combinedEffect:             null,
            combinedEffectOptions:      null,
            combinedEffectCallback:     null,

            clearto:                    'rgba(0,0,0,0)',

            dotted:                     false,
            dashed:                     false
        }


        //
        // Store the original data. This also allows for giving arguments as one big array.
        //
        this.original_data = [];

        if (typeof conf.data[0] === 'number' || RGraph.isNull(conf.data[0])) {
            this.original_data[0] = RGraph.arrayClone(conf.data);
        } else {
            for (var i=0; i<conf.data.length; ++i) {
                this.original_data[i] = RGraph.arrayClone(conf.data[i]);
            }
        }

        // Check for support
        if (!this.canvas) {
            alert('[LINE] Fatal error: no canvas support');
            return;
        }
        
        // Convert strings to numbers
        for (var i=0; i<this.original_data.length; ++i) {
            for (var j=0; j<this.original_data[i].length; ++j) {
                if (typeof this.original_data[i][j] === 'string') {
                    this.original_data[i][j] = parseFloat(this.original_data[i][j]);
                }
            }
        }

        
        //
        // Store the data here as one big array
        //
        this.data_arr = RGraph.arrayLinearize(this.original_data);

        for (var i=0; i<this.data_arr.length; ++i) {
            this['$' + i] = {};
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
        this.draw = function ()
        {
            // MUST be the first thing done!
            if (typeof prop.backgroundImage == 'string') {
                RGraph.drawBackgroundImage(this);
            }
    

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
            // Make the margins easy to access
            //
            this.marginLeft   = prop.marginLeft;
            this.marginRight  = prop.marginRight;
            this.marginTop    = prop.marginTop;
            this.marginBottom = prop.marginBottom;

    
    
            // Reset the data back to that which was initially supplied
            this.data = RGraph.arrayClone(this.original_data);

    
            // Reset the max value
            this.max = 0;

            if (prop.filled && !prop.filledRange && this.data.length > 1 && prop.filledAccumulative) {
    
                var accumulation = [];

                for (var set=0; set<this.data.length; ++set) {
                    for (var point=0; point<this.data[set].length; ++point) {
                        this.data[set][point] = Number(accumulation[point] ? accumulation[point] : 0) + this.data[set][point];
                        accumulation[point] = this.data[set][point];
                    }
                }
            }

            //
            // Get the maximum Y scale value
            //
            if (prop.yaxisScaleMax) {

                this.max = prop.yaxisScaleMax;
                this.min = prop.yaxisScaleMin ? prop.yaxisScaleMin : 0;
    
                this.scale2 = RGraph.getScale({object: this, options: {
                    'scale.max':          this.max,
                    'scale.min':          prop.yaxisScaleMin,
                    'scale.strict':       true,
                    'scale.thousand':     prop.yaxisScaleThousand,
                    'scale.point':        prop.yaxisScalePoint,
                    'scale.decimals':     prop.yaxisScaleDecimals,
                    'scale.labels.count': prop.yaxisLabelsCount,
                    'scale.round':        prop.yaxisScaleRound,
                    'scale.units.pre':    prop.yaxisScaleUnitsPre,
                    'scale.units.post':   prop.yaxisScaleUnitsPost
                }});

                this.max   = this.scale2.max ? this.scale2.max : 0;
    
                // Check for negative values
                if (!prop.outofbounds) {
                    for (dataset=0; dataset<this.data.length; ++dataset) {
                        if (RGraph.isArray(this.data[dataset])) {
                            for (var datapoint=0; datapoint<this.data[dataset].length; datapoint++) {
                                // Check for negative values
                                this.hasnegativevalues = (this.data[dataset][datapoint] < 0) || this.hasnegativevalues;
                            }
                        }
                    }
                }
    
            } else {

                this.min = prop.yaxisScaleMin ? prop.yaxisScaleMin : 0;
    
                // Work out the max Y value
                for (dataset=0; dataset<this.data.length; ++dataset) {
                    for (var datapoint=0; datapoint<this.data[dataset].length; datapoint++) {
        
                        this.max = Math.max(this.max, this.data[dataset][datapoint] ? Math.abs(parseFloat(this.data[dataset][datapoint])) : 0);
        
                        // Check for negative values
                        if (!prop.outofbounds) {
                            this.hasnegativevalues = (this.data[dataset][datapoint] < 0) || this.hasnegativevalues;
                        }
                    }
                }

                this.scale2 = RGraph.getScale({object: this, options: {
                    'scale.max':          this.max,
                    'scale.min':          prop.yaxisScaleMin,
                    'scale.thousand':     prop.yaxisScaleThousand,
                    'scale.point':        prop.yaxisScalePoint,
                    'scale.decimals':     prop.yaxisScaleDecimals,
                    'scale.labels.count': prop.yaxisLabelsCount,
                    'scale.round':        prop.yaxisScaleRound,
                    'scale.units.pre':    prop.yaxisScaleUnitsPre,
                    'scale.units.post':   prop.yaxisScaleUnitsPost,
                    'scale.formatter':    prop.yaxisScaleFormatter
                }});
    
                this.max   = this.scale2.max ? this.scale2.max : 0;
            }
    
            //
            // Setup the context menu if required
            //
            if (prop.contextmenu) {
                RGraph.showContext(this);
            }

            //
            // Reset the coords arrays otherwise it will keep growing
            //
            this.coords     = [];
            this.coordsText = [];

            //
            // Work out a few things. They need to be here because they depend on things you can change before you
            // call Draw() but after you instantiate the object
            //
            this.grapharea      = this.canvas.height - this.marginTop - this.marginBottom;
            this.halfgrapharea  = this.grapharea / 2;
            this.halfTextHeight = prop.textSize / 2;
    
   
    
            if (prop.variant == '3d') {
                RGraph.draw3DAxes(this);
            }
            
            // Draw the background
            RGraph.Background.draw(this);


            //
            // Draw any horizontal bars that have been defined
            //
            if (prop.backgroundHbars && prop.backgroundHbars.length > 0) {
                RGraph.drawBars(this);
            }

            if (!prop.axesAbove) {
                this.drawAxes();
            }
    
            //
            // This facilitates the new Trace2 effect
            //
    
            this.context.save()
            this.context.beginPath();

            // The clipping region is idfferent based on th animationTraceCenter option
            if (prop.animationTraceCenter) {
                this.context.rect(
                    (this.canvas.width / 2) * (1 - prop.animationTraceClip),
                    0,
                    this.canvas.width * prop.animationTraceClip,
                    this.canvas.height
                );
            } else {
                this.context.rect(0, 0, this.canvas.width * prop.animationTraceClip, this.canvas.height);
            }

            this.context.clip();
    
                for (var i=0, j=0, len=this.data.length; i<len; i++, j++) {
        
                    this.context.beginPath();
        
                    //
                    // Turn on the shadow if required
                    //
                    if (!prop.filled) {
                        this.setShadow(i);
                    }
        
                    //
                    // Draw the line
                    //
        
                    if (prop.filledColors) {
                        if (typeof prop.filledColors == 'object' && prop.filledColors[j]) {
                           var fill = prop.filledColors[j];
                        
                        } else if (typeof prop.filledColors == 'object' && prop.filledColors.toString().indexOf('Gradient') > 0) {
                           var fill = prop.filledColors;
                        
                        } else if (typeof prop.filledColors == 'string') {
                            var fill = prop.filledColors;
            
                        }
                    } else if (prop.filled) {
                        var fill = prop.colors[j];
        
                    } else {
                        var fill = null;
                    }

                    //
                    // Figure out the tickmark to use
                    //
                    if (prop.tickmarksStyle && typeof prop.tickmarksStyle == 'object') {
                        var tickmarks = prop.tickmarksStyle[i];
                    } else if (prop.tickmarksStyle && typeof prop.tickmarksStyle == 'string') {
                        var tickmarks = prop.tickmarksStyle;
                    } else if (prop.tickmarksStyle && typeof prop.tickmarksStyle == 'function') {
                        var tickmarks = prop.tickmarksStyle;
                    } else {
                        var tickmarks = null;
                    }

                    //
                    // Draw the line, accounting for the outofboundsClip option
                    //
                    if (prop.outofboundsClip) {
                        this.path(
                            'sa b r % % % % cl b',
                            0,this.marginTop,this.canvas.width,this.canvas.height - this.marginTop - this.marginBottom
                        );
                    }

                        this.drawLine(
                            this.data[i],
                            prop.colors[j],
                            fill,
                            this.getLineWidth(j),
                            tickmarks,
                            i
                        );
                    if (prop.outofboundsClip) {
                        this.context.restore();
                    }
            
                    this.context.stroke();

                }
        
            //
            // If the line is filled re-stroke the lines
            //
            if (prop.outofboundsClip) {
                this.path(
                    'sa b r % % % % cl b',
                    0,this.marginTop,this.canvas.width,this.canvas.height - this.marginTop - this.marginBottom
                );
            }


            if (prop.filled && prop.filledAccumulative && !prop.spline) {
                

                for (var i=0; i<this.coords2.length; ++i) {
        
                    this.context.beginPath();
                    this.context.lineWidth = this.getLineWidth(i);
                    this.context.strokeStyle = !this.hidden(i) ? prop.colors[i] : 'rgba(0,0,0,0)';
        
                    for (var j=0,len=this.coords2[i].length; j<len; ++j) {
        
                        if (j == 0 || this.coords2[i][j][1] == null || (this.coords2[i][j - 1] && this.coords2[i][j - 1][1] == null)) {
                            this.context.moveTo(this.coords2[i][j][0], this.coords2[i][j][1]);
                        } else {
                            if (prop.stepped) {
                                this.context.lineTo(this.coords2[i][j][0], this.coords2[i][j - 1][1]);
                            }
                            this.context.lineTo(this.coords2[i][j][0], this.coords2[i][j][1]);
                        }
                    }
                    
                    this.context.stroke();
                    // No fill!
                }

                // Redraw the tickmarks
                if (prop.tickmarksStyle) {
        
                    this.context.beginPath();
        
                    this.context.fillStyle = 'white';
                    
                    for (var i=0,len=this.coords2.length; i<len; ++i) {
        
                        this.context.beginPath();
                        this.context.strokeStyle = prop.colors[i];
    
                        for (var j=0; j<this.coords2[i].length; ++j) {
                            if (typeof this.coords2[i][j] == 'object' && typeof this.coords2[i][j][0] == 'number' && typeof this.coords2[i][j][1] == 'number') {
                                
                                var tickmarks = typeof prop.tickmarksStyle == 'object' ? prop.tickmarksStyle[i] : prop.tickmarksStyle;
        
                                this.drawTick(
                                    this.coords2[i],
                                    this.coords2[i][j][0],
                                    this.coords2[i][j][1],
                                    this.context.strokeStyle,
                                    false,
                                    j == 0 ? 0 : this.coords2[i][j - 1][0],
                                    j == 0 ? 0 : this.coords2[i][j - 1][1],
                                    tickmarks,
                                    j,
                                    i
                                );
                            }
                        }
                    }
        
                    this.context.stroke();
                    this.context.fill();
                }

            } else if (prop.filled && prop.filledAccumulative && prop.spline) {

                // Restroke the curvy filled accumulative lines

                for (var i=0; i<this.coordsSpline.length; i+=1) {
                    this.context.beginPath();
                    this.context.strokeStyle = prop.colors[i];
                    this.context.lineWidth = this.getLineWidth(i);

                    for (var j=0,len=this.coordsSpline[i].length; j<len; j+=1) {
                        
                        var point = this.coordsSpline[i][j];
                        
                        j == 0 ? this.context.moveTo(point[0], point[1]) : this.context.lineTo(point[0], point[1]);
                    }

                   this.context.stroke();
                }







                for (var i=0,len=this.coords2.length; i<len; i+=1) {
                    for (var j=0,len2=this.coords2[i].length; j<len2; ++j) {
                        if (typeof this.coords2[i][j] == 'object' && typeof this.coords2[i][j][0] == 'number' && typeof this.coords2[i][j][1] == 'number') {

                            var tickmarks = typeof prop.tickmarksStyle == 'object' && !RGraph.isNull(prop.tickmarksStyle) ? prop.tickmarksStyle[i] : prop.tickmarksStyle;
                            this.context.strokeStyle = prop.colors[i];
                            this.drawTick(
                                this.coords2[i],
                                this.coords2[i][j][0],
                                this.coords2[i][j][1],
                                prop.colors[i],
                                false,
                                j == 0 ? 0 : this.coords2[i][j - 1][0],
                                j == 0 ? 0 : this.coords2[i][j - 1][1],
                                tickmarks,
                                j,
                                i
                            );
                        }
                    }
                }



            }


        if (prop.outofboundsClip) {
            this.context.restore();
        }
        this.context.restore();
    
        // ???
        this.context.beginPath();
    
    
    
    
            //
            // If the axes have been requested to be on top, do that
            //
            if (prop.axesAbove) {
                this.drawAxes();
            }

            //
            // Draw the labels
            //
            this.drawLabels();
            
            //
            // Draw the range if necessary
            //
            this.drawRange();

            // Draw a key if necessary
            if (prop.key && prop.key.length && RGraph.drawKey) {
                RGraph.drawKey(this, prop.key, prop.colors);
            }
    
            //
            // Draw " above" labels if enabled
            //
            if (prop.labelsAbove) {
                this.drawAboveLabels();
            }
    
            //
            // Draw the "in graph" labels
            //
            RGraph.drawInGraphLabels(this);

            //
            // Redraw the lines if a filled range is on the cards
            //
            if (prop.filled && prop.filledRange && this.data.length == 2) {
    
                this.context.beginPath();
                var len        = this.coords.length / 2;
                this.context.lineWidth   = prop.linewidth;
                this.context.strokeStyle = this.hidden(0) ? 'rgba(0,0,0,0)' : prop.colors[0];
    
                for (var i=0; i<len; ++i) {
    
                    if (!RGraph.isNull(this.coords[i][1])) {
                        if (i == 0) {
                            this.context.moveTo(this.coords[i][0], this.coords[i][1]);
                        } else {
                            this.context.lineTo(this.coords[i][0], this.coords[i][1]);
                        }
                    }
                }
                
                this.context.stroke();
    
    
                this.context.beginPath();
                
                if (prop.colors[1]) {
                    this.context.strokeStyle = this.hidden(1) ? 'rgba(0,0,0,0)' : prop.colors[1];
                }
                
                for (var i=this.coords.length - 1; i>=len; --i) {
                    if (!RGraph.isNull(this.coords[i][1])) {
                        if (i == (this.coords.length - 1)) {
                            this.context.moveTo(this.coords[i][0], this.coords[i][1]);
                        } else {
                            this.context.lineTo(this.coords[i][0], this.coords[i][1]);
                        }
                    }
                }
    
                this.context.stroke();
    
    
            } else if (prop.filled && prop.filledRange) {
                alert('[LINE] You must have only two sets of data for a filled range chart');
            }

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
            // Add the tooltipsDataset tooltips listener
            //
            if (prop.tooltipsDataset) {
                this.addDatasetTooltip();
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
        // Draws the axes
        //
        this.drawAxes = function ()
        {
            this.context.beginPath();

            // Draw the X axis
            RGraph.drawXAxis(this);

            // Draw the Y axis
            RGraph.drawYAxis(this);

            //
            // This is here so that setting the color after this function doesn't
            // change the color of the axes
            //
            this.context.beginPath();
        };








        // Draw the text labels for the axes
        this.drawLabels = function ()
        {
            // Now done by the X and Y axis functions
        };








        //
        // Draws the line
        //
        this.drawLine = function (lineData, color, fill, linewidth, tickmarks, index)
        {
            // This facilitates the Rise animation (the Y value only)
            if (prop.animationUnfoldY && prop.animationFactor != 1) {
                for (var i=0; i<lineData.length; ++i) {
                    lineData[i] *= prop.animationFactor;
                }
            }

            var penUp = false;
            var yPos  = null;
            var xPos  = 0;
            this.context.lineWidth = 1;
            var lineCoords = [];
            
            //
            // Get the previous line data
            //
            if (index > 0) {
                var prevLineCoords = this.coords2[index - 1];
            }


            // Work out the X interval
            var xInterval = (this.canvas.width - (2 * prop.marginInner) - this.marginLeft - this.marginRight) / (lineData.length - 1);
    
            // Loop thru each value given, plotting the line
            // (FORMERLY FIRST)
            for (i=0,len=lineData.length; i<len; i+=1) {

                var data_point = lineData[i];
    
                //
                // Get the yPos for the given data point
                //
                var yPos = this.getYCoord(data_point);


                // Null data points, and a special case a bug
                if (   lineData[i] == null
                    || (prop.xaxisPosition == 'bottom' && lineData[i] < this.min && !prop.outofbounds)
                    ||  (prop.xaxisPosition == 'center' && lineData[i] < (-1 * this.max) && !prop.outofbounds)
                    || (((lineData[i] < this.min && prop.xaxisPosition !== 'center') || lineData[i] > this.max) && !prop.outofbounds)) {
    
                    yPos = null;
                }

                // Not always very noticeable, but it does have an effect
                // with thick lines
                this.context.lineCap  = 'round';
                this.context.lineJoin = 'round';
    
                // Plot the line if we're at least on the second iteration
                if (i > 0) {
                    xPos = xPos + xInterval;
                } else {
                    xPos = prop.marginInner + this.marginLeft;
                }
                
                if (prop.animationUnfoldX) {
                    xPos *= prop.animationFactor;
                    
                    if (xPos < prop.marginLeft) {
                        xPos = prop.marginLeft;
                    }
                }
    
                //
                // Add the coords to an array
                //
                this.coords.push([xPos, yPos]);
                lineCoords.push([xPos, yPos]);
            }

            this.context.stroke();

            // Store the coords in another format, indexed by line number
            this.coords2[index] = lineCoords;



            //
            // Now draw the actual line [FORMERLY SECOND]
            //
            this.context.beginPath();
            // Transparent now as of 11/19/2011
            this.context.strokeStyle = 'rgba(0,0,0,0)';
            //this.context.strokeStyle = fill;
            if (fill) {
                this.context.fillStyle   = fill;
            }

            var isStepped = prop.stepped;
            var isFilled  = prop.filled;
            
            if (prop.xaxisPosition == 'top') {
                var xAxisPos = this.marginTop;
            } else if (prop.xaxisPosition == 'center') {
                var xAxisPos = this.marginTop + (this.grapharea / 2);
            } else if (prop.xaxisPosition == 'bottom') {
                var xAxisPos = this.getYCoord(prop.yaxisScaleMin)

            }




            for (var i=0,len=lineCoords.length; i<len; i+=1) {
    
                xPos = lineCoords[i][0];
                yPos = lineCoords[i][1];
                var set = index;
    
                var prevY     = (lineCoords[i - 1] ? lineCoords[i - 1][1] : null);
                var isLast    = (i + 1) == lineCoords.length;
    
                //
                // This nullifys values which are out-of-range
                //
                if (!prop.outofbounds && (prevY < this.marginTop || prevY > (this.canvas.height - this.marginBottom) ) ) {
                    penUp = true;
                }
    
                if (i == 0 || penUp || !yPos || !prevY || prevY < this.marginTop) {

                    if (prop.filled && !prop.filledRange) {
    
                        if (!prop.outofbounds || prevY === null || yPos === null) {
                            this.context.moveTo(xPos + 1, xAxisPos);
                        }

                        // This facilitates the X axis being at the top
                        // NOTE: Also done below
                        if (prop.xaxisPosition == 'top') {
                            this.context.moveTo(xPos + 1, xAxisPos);
                        }
                        
                        if (isStepped && i > 0) {
                            this.context.lineTo(xPos, lineCoords[i - 1][1]);
                        }
    
                        this.context.lineTo(xPos, yPos);
    
                    } else {
    
                        if (RGraph.ISOLD && yPos == null) {
                            // Nada
                        } else {
                            this.context.moveTo(xPos + 1, yPos);
                        }
                    }
    
                    if (yPos == null) {
                        penUp = true;
    
                    } else {
                        penUp = false;
                    }
    
                } else {
    
                    // Draw the stepped part of stepped lines
                    if (isStepped) {
                        this.context.lineTo(xPos, lineCoords[i - 1][1]);
                    }
    
                    if ((yPos >= this.marginTop && yPos <= (this.canvas.height - this.marginBottom)) || prop.outofbounds ) {
    
                        if (isLast && prop.filled && !prop.filledRange && prop.yaxisPosition == 'right') {
                            xPos -= 1;
                        }
    
    
                        // Added 8th September 2009
                        if (!isStepped || !isLast) {
                            this.context.lineTo(xPos, yPos);
                            
                            if (isFilled && lineCoords[i+1] && lineCoords[i+1][1] == null) {
                                this.context.lineTo(xPos, xAxisPos);
                            }
                        
                        // Added August 2010
                        } else if (isStepped && isLast) {
                            this.context.lineTo(xPos,yPos);
                        }
    
    
                        penUp = false;
                    } else {
                        penUp = true;
                    }
                }
            }

            //
            // Draw a line to the X axis if the chart is filled
            //
            if (prop.filled && !prop.filledRange && !prop.spline) {

                // Is this needed ??
                var fillStyle = prop.filledColors;

                //
                // Draw the bottom edge of the filled bit using either the X axis or the prevlinedata,
                // depending on the index of the line. The first line uses the X axis, and subsequent
                // lines use the prevLineCoords array
                //
                if (index > 0 && prop.filledAccumulative) {
                    
                    this.context.lineTo(xPos, prevLineCoords ? prevLineCoords[i - 1][1] : (this.canvas.height - this.marginBottom - 1 + (prop.xaxisPosition == 'center' ? (this.canvas.height - this.marginTop - this.marginBottom) / 2 : 0)));

                    for (var k=(i - 1); k>=0; --k) {
                        this.context.lineTo(k == 0 ? prevLineCoords[k][0] + 1: prevLineCoords[k][0], prevLineCoords[k][1]);
                    }
                } else {

                    // Draw a line down to the X axis
                    if (prop.xaxisPosition == 'top') {
                        this.context.lineTo(xPos, prop.marginTop +  1);
                        this.context.lineTo(lineCoords[0][0],prop.marginTop + 1);
                    } else if (typeof lineCoords[i - 1][1] == 'number') {

                        var yPosition = this.getYCoord(0);

                        this.context.lineTo(xPos,yPosition);
                        this.context.lineTo(lineCoords[0][0],yPosition);
                    }
                }
    
                this.context.fillStyle = !this.hidden(index) ? fill : 'rgba(0,0,0,0)';

                this.context.fill();
                this.context.beginPath();

            }
    
            this.context.stroke();
    
    
            if (prop.backdrop) {
                this.drawBackdrop(lineCoords, color);
            }
    
    
    
    
            //
            // TODO CLIP TRACE
            // By using the clip() method the Trace animation can be updated.
            // NOTE: Needs to be done for the filled part as well
            // NOTE: This appears to have been done?
            //
            this.context.save();
                this.context.beginPath();
                
                // The clipping region is different based on th animationTraceCenter option
                if (prop.animationTraceCenter) {
                    this.context.rect(
                        (this.canvas.width / 2) * (1 - prop.animationTraceClip),
                        0,
                        this.canvas.width * prop.animationTraceClip,
                        this.canvas.height
                    );
                } else {
                    this.context.rect(0, 0, this.canvas.width * prop.animationTraceClip, this.canvas.height);
                }
                this.context.clip();





                //
                // Draw errorbars
                //
                if (typeof prop.errorbars !== 'null') {
                    this.drawErrorbars();
                }




                // Now redraw the lines with the correct line width
                this.setShadow(index);
                this.redrawLine(lineCoords, color, linewidth, index);
                this.context.stroke();
                RGraph.noShadow(this);






    
            // Draw the tickmarks
                for (var i=0; i<lineCoords.length; ++i) {
        
                    i = Number(i);
                    
                    //
                    // Set the color
                    //
                    this.context.strokeStyle = color;
                    
        
                    if (isStepped && i == (lineCoords.length - 1)) {
                        this.context.beginPath();
                        //continue;
                    }
        
                    if (
                        (
                            tickmarks != 'endcircle'
                         && tickmarks != 'endsquare'
                         && tickmarks != 'filledendsquare'
                         && tickmarks != 'endtick'
                         && tickmarks != 'endtriangle'
                         && tickmarks != 'arrow'
                         && tickmarks != 'filledarrow'
                        )
                        || (i == 0 && tickmarks != 'arrow' && tickmarks != 'filledarrow')
                        || i == (lineCoords.length - 1)
                       ) {
        
                        var prevX = (i <= 0 ? null : lineCoords[i - 1][0]);
                        var prevY = (i <= 0 ? null : lineCoords[i - 1][1]);

                        this.drawTick(
                            lineData,
                            lineCoords[i][0],
                            lineCoords[i][1],
                            color,
                            false,
                            prevX,
                            prevY,
                            tickmarks,
                            i,
                            index
                        );
                    }
                }
            
            this.context.restore();
    
            // Draw something off canvas to skirt an annoying bug
            this.context.beginPath();
            this.context.arc(this.canvas.width + 50000, this.canvas.height + 50000, 2, 0, 6.38, 1);
        };








        //
        // This functions draws a tick mark on the line
        //
        this.drawTick = function (lineData, xPos, yPos, color, isShadow, prevX, prevY, tickmarks, index, dataset)
        {
            // Various conditions mean no tick
            if (this.hidden(dataset)) {
                return;
            } else if (RGraph.isNull(yPos)) {
                return false;
            } else if ((yPos > (this.canvas.height - this.marginBottom)) && !prop.outofbounds) {
                return;
             } else if ((yPos < this.marginTop) && !prop.outofbounds) {
                return;
            }

            this.context.beginPath();
    
            var offset   = 0;
    
            // Reset the stroke and lineWidth back to the same as what they were when the line was drawm
            // UPDATE 28th July 2011 - the line width is now set to 1
            this.path(
                'lw % ss % fs %',
                prop.tickmarksLinewidth ? prop.tickmarksLinewidth : prop.linewidth,
                isShadow ? prop.shadowColor : this.context.strokeStyle,
                isShadow ? prop.shadowColor : this.context.strokeStyle
            );

    
            // Cicular tick marks
            if (   tickmarks == 'circle'
                || tickmarks == 'round'
                || tickmarks == 'filledcircle'
                || tickmarks == 'endcircle'
                || tickmarks === 'filledendcircle') {

                if (tickmarks == 'round'|| tickmarks == 'circle'|| tickmarks == 'filledcircle' || ((tickmarks == 'endcircle' || tickmarks === 'filledendcircle') && (index == 0 || index == (lineData.length - 1)))) {
                    this.path(
                        'b a % % % % % %',
                        xPos + offset,yPos + offset,prop.tickmarksSize,0,360 / (180 / RGraph.PI),false
                    );

                    if (tickmarks.indexOf('filled') !== -1) {
                        this.path(
                            'fs %',
                            isShadow ? prop.shadowColor : this.context.strokeStyle
                        );

                    } else {

                        this.path(
                            'fs %',
                            isShadow ? prop.shadowColor : 'white'
                        );
                    }

                    this.context.fill();
                    this.context.stroke();
                }
    
            // Halfheight "Line" style tick marks
            } else if (tickmarks == 'halftick') {
                this.path(
                    'b m % % l % % s null',
                    Math.round(xPos), yPos,
                    Math.round(xPos), yPos + prop.tickmarksSize
                );
            
            // Tick style tickmarks
            } else if (tickmarks == 'tick') {
                this.path(
                    'b m % % l % % s',
                    Math.round(xPos), yPos -  prop.tickmarksSize,
                    Math.round(xPos), yPos + prop.tickmarksSize
                );
            
            // Endtick style tickmarks
            } else if (tickmarks == 'endtick' && (index == 0 || index == (lineData.length - 1))) {
                this.path(
                    'b m % % l % % s',
                    Math.round(xPos), yPos -  prop.tickmarksSize,
                    Math.round(xPos), yPos + prop.tickmarksSize
                );
            
            // "Cross" style tick marks
            } else if (tickmarks == 'cross') {
                
                var ticksize = prop.tickmarksSize;

                this.path(
                    'b m % % l % % m % % l % % s null',
                    xPos - ticksize, yPos - ticksize,
                    xPos + ticksize, yPos + ticksize,
                    xPos + ticksize, yPos - ticksize,
                    xPos - ticksize, yPos + ticksize
                );
    
    
            // Triangle style tick marks
            } else if (tickmarks == 'triangle' || tickmarks == 'filledtriangle' || (tickmarks == 'endtriangle' && (index == 0 || index == (lineData.length - 1)))) {
                
                this.path(
                    'b m % % l % % l % % c f % s null',
                    Math.round(xPos - prop.tickmarksSize), yPos + prop.tickmarksSize,
                    Math.round(xPos), yPos - prop.tickmarksSize,
                    Math.round(xPos + prop.tickmarksSize), yPos + prop.tickmarksSize,
                    tickmarks === 'filledtriangle' ? (isShadow ? prop.shadowColor : this.context.strokeStyle) : 'white'
                );
    
    
            // 
            // A white bordered circle
            //
            } else if (tickmarks == 'borderedcircle' || tickmarks == 'dot') {

                this.path(
                    'lw % b a % % % % % false c f % s %',
                    prop.tickmarksStyleDotLinewidth || 0.00000001,
                    xPos, yPos, prop.tickmarksSize, 0, 360 / (180 / RGraph.PI),
                    prop.tickmarksStyleDotFill || color,
                    prop.tickmarksStyleDotStroke || color
                );
            
            } else if (   tickmarks == 'square'
                       || tickmarks == 'rect'
                       || tickmarks == 'filledsquare'
                       || (tickmarks == 'endsquare' && (index == 0 || index == (lineData.length - 1)))
                       || (tickmarks == 'filledendsquare' && (index == 0 || index == (lineData.length - 1))) ) {

                this.path(
                    'b r % % % % f % s %',
                    Math.round(xPos - prop.tickmarksSize),
                    Math.round(yPos - prop.tickmarksSize),
                    prop.tickmarksSize * 2,
                    prop.tickmarksSize * 2,
                    'white',
                    this.context.strokeStyle
                );

                // Fillrect
                if (tickmarks == 'filledsquare' || tickmarks == 'filledendsquare') {
                
                    this.path(
                        'b r % % % % f %',
                        Math.round(xPos - prop.tickmarksSize),
                        Math.round(yPos - prop.tickmarksSize),
                        prop.tickmarksSize * 2,
                        prop.tickmarksSize * 2,
                        isShadow ? prop.shadowColor : this.context.strokeStyle
                    );
                }
                
                this.path('f null s null');




            //
            // Filled arrowhead
            //
            } else if (tickmarks == 'filledarrow') {


                // If the spline option is enabled then update the
                // variables that are used to calculate the arrow
                if (prop.spline) {
                    xPos = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 1][0];
                    yPos = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 1][1];
                    
                    prevX = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 3][0];
                    prevY = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 3][1];
                }


                var x = Math.abs(xPos - prevX);
                var y = Math.abs(yPos - prevY);
    
                if (yPos < prevY) {
                    var a = Math.atan(x / y) + 1.57;
                } else {
                    var a = Math.atan(y / x) + 3.14;
                }

                this.path(
                    'b lj miter m % % a % % % % % false a % % % % % false c s % f %',
                    xPos, yPos,
                    xPos, yPos, prop.tickmarksSize, a - 0.3, a - 0.3,
                    xPos, yPos, prop.tickmarksSize, a + 0.3, a + 0.3,
                    this.context.strokeStyle,
                    this.context.fillStyle
                );

            //
            // Arrow head, NOT filled
            //
            } else if (tickmarks === 'arrow') {


                // If the spline option is enabled then update the
                // variables that are used to calculate the arrow
                if (prop.spline) {
                    xPos = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 1][0];
                    yPos = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 1][1];
                    
                    prevX = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 2][0];
                    prevY = this.coordsSpline[dataset][this.coordsSpline[dataset].length - 2][1];
                }


                var orig_linewidth = this.context.lineWidth;
    
                var x = Math.abs(xPos - prevX);
                var y = Math.abs(yPos - prevY);
                
                this.context.lineWidth;
    
                if (yPos < prevY) {
                    var a = Math.atan(x / y) + 1.57;
                } else {
                    var a = Math.atan(y / x) + 3.14;
                }
                
                this.path(
                    'b lj miter m % % a % % % % % false m % % a % % % % % false s % lw %',
                    xPos, yPos,
                    xPos, yPos, prop.tickmarksSize, a - 0.3, a - 0.3,
                    xPos, yPos,
                    xPos, yPos, prop.tickmarksSize, a + 0.3, a + 0.3,
                    this.context.strokeStyle,
                    orig_linewidth
                );

            //
            // Image based tickmark
            //
            // lineData, xPos, yPos, color, isShadow, prevX, prevY, tickmarks, index
            } else if (
                       typeof tickmarks === 'string' &&
                           (
                            tickmarks.substr(0, 6) === 'image:'  ||
                            tickmarks.substr(0, 5) === 'data:'   ||
                            tickmarks.substr(0, 1) === '/'       ||
                            tickmarks.substr(0, 3) === '../'     ||
                            tickmarks.substr(0, 7) === 'images/' ||
                            tickmarks.substr(0, 4) === 'src:'
                           )
                      ) {

                var img = new Image();
                
                if (tickmarks.substr(0, 6) === 'image:') {
                    img.src = tickmarks.substr(6);
                } else if (tickmarks.substr(0, 4) === 'src:') {
                    img.src = tickmarks.substr(4);
                } else {
                    img.src = tickmarks;
                }

                var obj = this;

                img.onload = function ()
                {
                    if (prop.tickmarksStyleImageHalign === 'center') xPos -= (this.width / 2);
                    if (prop.tickmarksStyleImageHalign === 'right')  xPos -= this.width;
                    
                    if (prop.tickmarksStyleImageValign === 'center') yPos -= (this.height / 2);
                    if (prop.tickmarksStyleImageValign === 'bottom') yPos -= this.height;
                    
                    xPos += prop.tickmarksStyleImageOffsetx;
                    yPos += prop.tickmarksStyleImageOffsety;

                    obj.context.drawImage(this, xPos, yPos);
                };


            //
            // Custom tick drawing function
            //
            } else if (typeof tickmarks == 'function') {
                tickmarks(
                    this,
                    lineData, lineData[index],
                    index,
                    xPos, yPos,
                    color,
                    prevX, prevY
                );
            }
        };








        //
        // Draws a filled range if necessary
        //
        this.drawRange = function ()
        {
            //
            // Fill the range if necessary
            //
            if (prop.filledRange && prop.filled) {
            
                if (RGraph.isNull(prop.filledRangeThreshold)) {
                    prop.filledRangeThreshold        = this.ymin
                    prop.filledRangeThresholdColors = [prop.filledColors, prop.filledColors]
                }
    
                for (var idx=0; idx<2; ++idx) {
    
                    var threshold_colors = prop.filledRangeThresholdColors;
                    var y = this.getYCoord(prop.filledRangeThreshold)
                    
                    this.context.save();
                        if (idx == 0) {
                            this.context.beginPath();
                            this.context.rect(0,0,this.canvas.width,y);
                            this.context.clip();
                        
                        } else {
    
                            this.context.beginPath();
                            this.context.rect(0,y,this.canvas.width, this.canvas.height);
                            this.context.clip();
                        }
    
                        this.context.beginPath();
                            this.context.fillStyle = (idx == 1 ? prop.filledRangeThresholdColors[1] : prop.filledRangeThresholdColors[0]);

                            this.context.lineWidth = !this.hidden(idx) ? 1 : 0;
                            var len = (this.coords.length / 2);
                
                            
                            
                            for (var i=0; i<len; ++i) {
                                if (!RGraph.isNull(this.coords[i][1])) {
                                    if (i == 0) {
                                        this.context.moveTo(this.coords[i][0], this.coords[i][1])
                                    } else {
                                        this.context.lineTo(this.coords[i][0], this.coords[i][1])
                                    }
                                }
                            }
    
    
                            for (var i=this.coords.length - 1; i>=len; --i) {
                                if (RGraph.isNull(this.coords[i][1])) {
                                    this.context.moveTo(this.coords[i][0], this.coords[i][1])
                                } else {
                                    this.context.lineTo(this.coords[i][0], this.coords[i][1])
                                }
                                //this.context.lineTo(this.coords[i][0], this.coords[i][1])
                            }
    
    
    
                        // Taken out - 10th Oct 2012
                        //this.context.stroke();
            
                        this.context.fill();
                    this.context.restore();
                }
            }
        };








        //
        // Redraws the line with the correct line width etc
        // 
        // @param array coords The coordinates of the line
        //
        this.redrawLine = function (coords, color, linewidth, index)
        {
            if (!prop.redraw || prop.filledRange) {
                return;
            }
    

            
            this.context.strokeStyle = (typeof color == 'object' && color && color.toString().indexOf('CanvasGradient') == -1 ? color[0] : color);
            this.context.lineWidth = linewidth;


            // Added this on 1/1/17 to facilitate dotted and dashed lines
            if (prop.dotted || prop.dashed ) {
                if (prop.dashed) {
                    this.context.setLineDash([2,6])
                } else if (prop.dotted) {
                    this.context.setLineDash([1,5])
                }
            }



            if (this.hidden(index)) {
                this.context.strokeStyle = 'rgba(0,0,0,0)';
            }








            if (prop.spline) {
                this.drawCurvyLine(coords, this.hidden(index) ? 'rgba(0,0,0,0)' : color, linewidth, index);
                return;
            }


            this.context.beginPath();
    
            var len    = coords.length;
            var width  = this.canvas.width
            var height = this.canvas.height;
            var penUp  = false;
    
            for (var i=0; i<len; ++i) {
    
                var xPos   = coords[i][0];
                var yPos   = coords[i][1];
    
                if (i > 0) {
                    var prevX = coords[i - 1][0];
                    var prevY = coords[i - 1][1];
                }
    
    
                if ((
                       (i == 0 && coords[i])
                    || (yPos < this.marginTop)
                    || (prevY < this.marginTop)
                    || (yPos > (height - this.marginBottom))
                    || (i > 0 && prevX > (width - this.marginRight))
                    || (i > 0 && prevY > (height - this.marginBottom))
                    || prevY == null
                    || penUp == true
                   ) && (!prop.outofbounds || yPos == null || prevY == null) ) {

                    if (RGraph.ISOLD && yPos == null) {
                        // ...?
                    } else {
                        this.context.moveTo(coords[i][0], coords[i][1]);
                    }
    
                    penUp = false;
    
                } else {
    
                    if (prop.stepped && i > 0) {
                        this.context.lineTo(coords[i][0], coords[i - 1][1]);
                    }
                    
                    // Don't draw the last bit of a stepped chart. Now DO
                    //if (!this.properties.stepped || i < (coords.length - 1)) {
                    this.context.lineTo(coords[i][0], coords[i][1]);
                    //}
                    penUp = false;
                }
            }

            //
            // If two colors are specified instead of one, go over the up bits
            //
            if (prop.colorsAlternate && typeof color == 'object' && color[0] && color[1]) {
                for (var i=1; i<len; ++i) {
    
                    var prevX = coords[i - 1][0];
                    var prevY = coords[i - 1][1];
                    
                    if (prevY != null && coords[i][1] != null) {
                        this.context.beginPath();
                            this.context.strokeStyle = color[coords[i][1] < prevY ? 0 : 1];
                            this.context.lineWidth = prop.linewidth;
                            this.context.moveTo(prevX, prevY);
                            this.context.lineTo(coords[i][0], coords[i][1]);
                        this.context.stroke();
                    }
                }
            }
            


            // Added the stroke and beginPath in on 5/1/19 as dotted/dashed
            // wasn't working correctly.
            //
            this.context.stroke();
            this.context.beginPath();            
            if (prop.dashed || prop.dotted) {
                this.context.setLineDash([1,0]);
            }
        };








        //
        // Draw the backdrop
        //
        this.drawBackdrop = function (coords, color)
        {
            var size = prop.backdropSize;
            this.context.lineWidth = size;
            this.context.globalAlpha = prop.backdropAlpha;
            this.context.strokeStyle = color;
            var yCoords = [];
    
            this.context.beginPath();
                if (prop.spline && !RGraph.ISOLD) {
                    
                    // The DrawSpline function only takes the Y coords so extract them from the coords that have
                    // (which are X/Y pairs)
                    for (var i=0; i<coords.length; ++i) {
                        yCoords.push(coords[i][1])
                    }

                    this.drawSpline(this.context, yCoords, color, null);
    
                } else {
                    this.context.moveTo(coords[0][0], coords[0][1]);
                    for (var j=1; j<coords.length; ++j) {
                        this.context.lineTo(coords[j][0], coords[j][1]);
                    }
                }
            this.context.stroke();
        
            // Reset the alpha value
            this.context.globalAlpha = 1;
            RGraph.noShadow(this);
        };








        //
        // Returns the linewidth
        //
        this.getLineWidth = function (i)
        {
            var linewidth = prop.linewidth;

            if (typeof linewidth == 'number') {
                return linewidth;
            
            } else if (typeof linewidth === 'object') {
                if (linewidth[i]) {
                    return linewidth[i];
                } else {
                    return linewidth[0];
                }
    
                alert('[LINE] Error! The linewidth option should be a single number or an array of one or more numbers');
            }
        };








        //
        // The getShape() method - used to get the point the mouse is currently over, if any
        // 
        // @param object e The event object
        // @param object   OPTIONAL You can pass in the bar object instead of the
        //                          function getting it from the canvas
        //
        this.getShape = function (e)
        {
            var obj     = this,
                mouseXY = RGraph.getMouseXY(e),
                mouseX  = mouseXY[0],
                mouseY  = mouseXY[1];
            
            // This facilitates you being able to pass in the bar object as a parameter instead of
            // the function getting it from the object
            if (arguments[1]) {
                obj = arguments[1];
            }

            for (var i=0; i<obj.coords.length; ++i) {
            
                var x = obj.coords[i][0],
                    y = obj.coords[i][1],
              dataset = 0,
                  idx = i;

                while ((idx + 1) > this.data[dataset].length) {
                    idx -= this.data[dataset].length;
                    dataset++;
                }

                // Do this if the hotspot is triggered by the X coord AND the Y coord
                if (   mouseX <= (x + prop.tooltipsHotspotSize)
                    && mouseX >= (x - prop.tooltipsHotspotSize)
                    && mouseY <= (y + prop.tooltipsHotspotSize)
                    && mouseY >= (y - prop.tooltipsHotspotSize)
                   ) {
    
                        if (RGraph.parseTooltipText) {
                            var tooltip = RGraph.parseTooltipText(prop.tooltips, i);
                        }

                        // Don't return points for hidden datasets
                        // Added 10/08/17
                        // Fixed 22/09/17 Thanks to zsolt - this should be a continue
                        // not a return.
                        if (this.hidden(dataset)) {
                            continue;
                        }

                        return {
                            object: obj,
                                 x: x,
                                 y: y,
                           dataset: dataset,
                             index: idx,
                   sequentialIndex: i,
                             label: prop.xaxisLabels && typeof prop.xaxisLabels[idx] === 'string' ? prop.xaxisLabels[idx] : null,
                           tooltip: typeof tooltip === 'string' ? tooltip : null
                        };
    
                } else if (    prop.tooltipsHotspotXonly == true
                            && mouseX <= (x + prop.tooltipsHotspotSize)
                            && mouseX >= (x - prop.tooltipsHotspotSize)) {
    
                            var tooltip = RGraph.parseTooltipText(prop.tooltips, i);
    
                            return {
                                object: obj,
                                     x: x,
                                     y: y,
                               dataset: dataset,
                                 index: idx,
                       sequentialIndex: i,
                                 label: prop.xaxisLabels && typeof prop.xaxisLabels[idx] === 'string' ? prop.xaxisLabels[idx] : null,
                               tooltip: tooltip
                            };
                }
            }
        };








        //
        // Draws the above line labels
        //
        this.drawAboveLabels = function ()
        {
            var units_pre  = prop.labelsAboveUnitsPre,
                units_post = prop.labelsAboveUnitsPost,
                decimals   = prop.labelsAboveDecimals,
                point      = prop.labelsAbovePoint,
                thousand   = prop.labelsAboveThousand,
                bgcolor    = prop.labelsAboveBackground || 'white',
                border     = ((
                       typeof prop.labelsAboveBorder === 'boolean'
                    || typeof prop.labelsAboveBorder === 'number'
                ) ? prop.labelsAboveBorder : true),
                offsety  = prop.labelsAboveOffsety,
                specific = prop.labelsAboveSpecific;


                var textConf = RGraph.getTextConf({
                    object: this,
                    prefix: 'labelsAbove'
                });
                
                offsety += textConf.size;



            // Use this to 'reset' the drawing state
            this.context.beginPath();
    
            // Don't need to check that chart.labels.above is enabled here, it's been done already
            for (var i=0, len=this.coords.length; i<len; i+=1) {

                var indexes = RGraph.sequentialIndexToGrouped (i, this.data),
                    dataset = indexes[0],
                    index   = indexes[1],
                    coords  = this.coords[i];
                
                // Don't draw a label for null values
                if (RGraph.isNull(coords[1])) {
                    continue;
                }
                
                if (this.hidden(dataset)) {
                    continue;
                }

                RGraph.text({
                
               object: this,
            
                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:              coords[0],
                    y:              coords[1] - offsety,
                    text:           (specific && specific[i]) ? specific[i] : (specific ? '' : RGraph.numberFormat({
                                        object:    this,
                                        number:    typeof decimals === 'number' ? this.data_arr[i].toFixed(decimals) : this.data_arr[i],
                                        unitspre:  units_pre,
                                        unitspost: units_post,
                                        point:     point,
                                        thousand:  thousand
                                    })),
                    valign:         'center',
                    halign:         'center',
                    bounding:       true,
                    boundingFill:   bgcolor,
                    boundingStroke: border ? 'black' : 'rgba(0,0,0,0)',
                    tag:            'labels.above'
                });
            }
        };








        //
        // Draw a curvy line.
        //
        this.drawCurvyLine = function (coords, color, linewidth, index)
        {
            var yCoords = [];
    
            for (var i=0; i<coords.length; ++i) {
                yCoords.push(coords[i][1]);
            }
            
            if (prop.filled) {
                this.context.beginPath();
                    
                    var xaxisY = this.getYCoord(prop.yaxisScaleMin);



                    this.context.moveTo(coords[0][0],xaxisY);
                    this.drawSpline(this.context, yCoords, color, index);

                    if (prop.filledAccumulative && index > 0) {
                        for (var i=(this.coordsSpline[index - 1].length - 1); i>=0; i-=1) {
                            this.context.lineTo(this.coordsSpline[index - 1][i][0], this.coordsSpline[index - 1][i][1]);
                        }
                    } else {
                        this.context.lineTo(coords[coords.length - 1][0],xaxisY);
                    }
                this.context.fill();
            }

            this.context.beginPath();    
            this.drawSpline(this.context, yCoords, color, index);
            this.context.stroke();
        };








        //
        // When you click on the chart, this method can return the Y value at that point. It works for any point on the
        // chart (that is inside the gutters) - not just points on the Line.
        // 
        // @param object e The event object
        //
        this.getValue = function (arg)
        {
            if (arg.length == 2) {
                var mouseX = arg[0];
                var mouseY = arg[1];
            } else {
                var mouseCoords = RGraph.getMouseXY(arg);
                var mouseX      = mouseCoords[0];
                var mouseY      = mouseCoords[1];
            }
    
            var obj = this;
            var xaxispos = prop.xaxisPosition;
    
            if (mouseY < prop.marginTop) {
                return xaxispos == 'bottom' || xaxispos == 'center' ? this.max : this.min;
            } else if (mouseY > (this.canvas.height - prop.marginBottom)) {
                return xaxispos == 'bottom' ? this.min : this.max;
            }

            if (prop.xaxisPosition == 'center') {
                var value = (( (obj.grapharea / 2) - (mouseY - prop.marginTop)) / obj.grapharea) * (obj.max - obj.min);
                value *= 2;
                value > 0 ? value += this.min : value -= this.min;
                return value;
            } else if (prop.xaxisPosition == 'top') {
                var value = ((obj.grapharea - (mouseY - prop.marginTop)) / obj.grapharea) * (obj.max - obj.min);
                value = Math.abs(obj.max - value) * -1;
                return value;
            } else {
                var value = ((obj.grapharea - (mouseY - prop.marginTop)) / obj.grapharea) * (obj.max - obj.min)
                value += obj.min;
                return value;
            }
        };








        //
        // Each object type has its own Highlight() function which highlights the appropriate shape
        // 
        // @param object shape The shape to highlight
        //
        this.highlight = function (shape)
        {
            if (prop.tooltipsHighlight) {
                
                if (typeof prop.highlightStyle === 'function') {
                    (prop.highlightStyle)(shape);
                
                // Inverted highlighting
                } else if (prop.highlightStyle === 'invert') {

                    // Clip to the graph area
                    this.path(
                        'sa b r % % % % cl',
                        prop.marginLeft, prop.marginTop,
                        this.canvas.width - prop.marginLeft - prop.marginRight,
                        this.canvas.height - prop.marginTop - prop.marginBottom 
                    );

                    this.path(
                        'b m % % a % % 25 4.71 4.72 true l % % l % % l % % l % % l % % c f %',
                        shape.x, prop.marginTop,
                        shape.x, shape.y,
                        shape.x, prop.marginTop,
                        this.canvas.width - prop.marginRight, prop.marginTop,
                        this.canvas.width - prop.marginRight, this.canvas.height - prop.marginBottom,
                        prop.marginLeft, this.canvas.height - prop.marginBottom,
                        prop.marginLeft, prop.marginTop,
                        prop.highlightFill
                    );

                    // Draw a border around the circular cutout
                    this.path(
                        'b a % % 25 0 6.29 false s % rs',
                        shape.x, shape.y,
                        prop.highlightStroke
                    );

                // Halo style highlighting
                } else if (prop.highlightStyle === 'halo') {
                    
                    var obj   = shape.object,
                        color = prop.colors[shape.dataset];

                    // Clear a space in white first for the tickmark
                    obj.path(
                        'b a % % 13 0 6.2830 false f rgba(255,255,255,0.75)',
                        shape.x,
                        shape.y
                    );
                    
                    obj.path(
                        'ga 0.15 b a % % 13 0 6.2830 false f % ga 1',
                        shape.x,
                        shape.y,
                        color
                    );
            
                    obj.path(
                        'b a % % 7 0 6.2830 false f white',
                        shape.x,
                        shape.y
                    );
                    
                    obj.path(
                        'b a % % 5 0 6.2830 false f %',
                        shape.x,
                        shape.y,
                        color
                    );
                
                } else {
                    RGraph.Highlight.point(this, shape);
                }
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
    
            // The 5 is so that the cursor doesn't have to be over the graphArea to trigger the hotspot
            if (
                   (mouseXY[0] > prop.marginLeft - 5)
                && mouseXY[0] < (this.canvas.width - prop.marginRight + 5)
                && mouseXY[1] > (prop.marginTop - 5)
                && mouseXY[1] < (this.canvas.height - prop.marginBottom + 5)
                ) {
    
                return this;
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
    
                // Rounding the value to the given number of decimals make the chart step
                var value   = Number(this.getValue(e));
                var shape   = RGraph.Registry.get('adjusting.shape');
    
                if (shape) {
    
                    RGraph.Registry.set('adjusting.shape', shape);
    
                    this.original_data[shape.dataset][shape.index] = Number(value);
    
                    RGraph.redrawCanvas(e.target);
                    
                    RGraph.fireCustomEvent(this, 'onadjust');
                }
            }
        };








        //
        // This function can be used when the canvas is clicked on (or similar - depending on the event)
        // to retrieve the relevant Y coordinate for a particular value.
        // 
        // @param int value The value to get the Y coordinate for
        //
        this.getYCoord = function (value)
        {
            if (typeof value != 'number') {
                return null;
            }
    
            var y;
            var xaxispos = prop.xaxisPosition;
    
            if (xaxispos == 'top') {
            
                // Account for negative numbers
                //if (value < 0) {
                //    value = Math.abs(value);
                //}
    
                y = ((value - this.min) / (this.max - this.min)) * this.grapharea;
    
                // Inverted Y labels
                if (prop.yaxisScaleInvert) {
                    y = this.grapharea - y;
                }
    
                y = y + this.marginTop
    
            } else if (xaxispos == 'center') {
    
                y = ((value - this.min) / (this.max - this.min)) * (this.grapharea / 2);
                y = (this.grapharea / 2) - y;
                y += this.marginTop;
    
            } else {
    
                if ((value < this.min || value > this.max) && prop.outofbounds == false) {
                    return null;
                }
    
                y = ((value - this.min) / (this.max - this.min)) * this.grapharea;
    
    
                
                // Inverted Y labels
                if (prop.yaxisScaleInvert) {
                    y = this.grapharea - y;
                }
                
                y = this.canvas.height - this.marginBottom - y;
            }

            return y;
        };








        //
        // This function draws a curvy line
        // 
        // @param object context The  2D context
        // @param array  coords  The coordinates
        //
        this.drawSpline = function (context, coords, color, index)
        {
            this.coordsSpline[index] = [];
            var xCoords     = [];
            var marginLeft  = prop.marginLeft;
            var marginRight = prop.marginRight;
            var hmargin     = prop.marginInner;
            var interval    = (this.canvas.width - (marginLeft + marginRight) - (2 * hmargin)) / (coords.length - 1);
    
            this.context.strokeStyle = color;

            //
            // The drawSpline function takes an array of JUST Y coords - not X/Y coords. So the line coords need converting
            // if we've been given X/Y pairs
            //
            for (var i=0,len=coords.length; i<len;i+=1) {
                if (typeof coords[i] == 'object' && coords[i] && coords[i].length == 2) {
                    coords[i] = Number(coords[i][1]);
                }
            }




            //
            // Get the Points array in the format we want - first value should be null along with the lst value
            //
            var P = [coords[0]];
            for (var i=0; i<coords.length; ++i) {
                P.push(coords[i]);
            }
            P.push(coords[coords.length - 1] + (coords[coords.length - 1] - coords[coords.length - 2]));
    
            for (var j=1; j<P.length-2; ++j) {
                for (var t=0; t<10; ++t) {
                    
                    var yCoord = Spline( t/10, P[j-1], P[j], P[j+1], P[j+2] );
    
                    xCoords.push(((j-1) * interval) + (t * (interval / 10)) + marginLeft + hmargin);

                    this.context.lineTo(xCoords[xCoords.length - 1], yCoord);

                    
                    if (typeof index == 'number') {
                        this.coordsSpline[index].push(
                            [xCoords[xCoords.length - 1],
                            yCoord]
                        );
                    }
                }
            }


            // Draw the last section
            this.context.lineTo(((j-1) * interval) + marginLeft + hmargin, P[j]);
            if (typeof index == 'number') {
                this.coordsSpline[index].push([((j-1) * interval) + marginLeft + hmargin, P[j]]);
            }


    
            function Spline (t, P0, P1, P2, P3)
            {
                return 0.5 * ((2 * P1) +
                             ((0-P0) + P2) * t +
                             ((2*P0 - (5*P1) + (4*P2) - P3) * (t*t) +
                             ((0-P0) + (3*P1)- (3*P2) + P3) * (t*t*t)));
            }
        };








        //
        // This allows for easy specification of gradients
        //
        this.parseColors = function ()
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (this.original_colors.length === 0) {
                this.original_colors.colors                = RGraph.arrayClone(prop.colors);
                this.original_colors.fillledColors         = RGraph.arrayClone(prop.filledColors);
                this.original_colors.keyColors             = RGraph.arrayClone(prop.keyColors);
                this.original_colors.backgroundBarsColor1  = prop.backgroundBarsColor1;
                this.original_colors.backgroundBarsColor2  = prop.backgroundBarsColor2;
                this.original_colors.backgroundGridColor   = prop.backgroundGridColor;
                this.original_colors.backgroundColor       = prop.backgroundColor;
                this.original_colors.textColor             = prop.textColor;
                this.original_colors.crosshairsColor       = prop.crosshairsColor;
                this.original_colors.annotatableColor      = prop.annotatableColor;
                this.original_colors.titleColor            = prop.titleColor;
                this.original_colors.xaxisTitleColor       = prop.xaxisTitleColor;
                this.original_colors.yaxisTitleColor       = prop.yaxisTitleColor;
                this.original_colors.keyBackground         = prop.keyBackground;
                this.original_colors.axesColor             = prop.axesColor;
                this.original_colors.highlightFill         = prop.highlightFill;
            }
            
            
            
            for (var i=0; i<prop.colors.length; ++i) {
                if (typeof prop.colors[i] == 'object' && prop.colors[i][0] && prop.colors[i][1]) {
                    prop.colors[i][0] = this.parseSingleColorForGradient(prop.colors[i][0]);
                    prop.colors[i][1] = this.parseSingleColorForGradient(prop.colors[i][1]);
                } else {
                    prop.colors[i] = this.parseSingleColorForGradient(prop.colors[i]);
                }
            }
            
            //
            // Filled.colors
            //
            if (prop.filledColors) {
                if (typeof prop.filledColors == 'string') {
                    prop.filledColors = this.parseSingleColorForGradient(prop.filledColors, 'vertical');
                } else {
                    for (var i=0; i<prop.filledColors.length; ++i) {
                        prop.filledColors[i] = this.parseSingleColorForGradient(prop.filledColors[i], 'vertical');
                    }
                }
            }
            
            //
            // Key colors
            //
            if (!RGraph.isNull(prop.keyColors)) {
                for (var i=0; i<prop.keyColors.length; ++i) {
                    prop.keyColors[i] = this.parseSingleColorForGradient(prop.keyColors[i]);
                }
            }
    
            //
            // Parse various properties for colors
            //
            var properties = [
                'backgroundBarsColor1',
                'backgroundBarsColor2',
                'backgroundGridColor',
                'backgroundColor',
                'crosshairsColor',
                'annotatableColor',
                'textColor',
                'titleColor',
                'xaxisTitleColor',
                'yaxisTitleColor',
                'keyBackground',
                'axesColor',
                'highlightFill'
            ];
    
            for (var i=0; i<properties.length; ++i) {
                prop[properties[i]] = this.parseSingleColorForGradient(prop[properties[i]]);
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
        this.parseSingleColorForGradient = function (color)
        {
            if (!color || typeof color != 'string') {
                return color;
            }

            //
            // Horizontal or vertical gradient
            //
            var dir = typeof arguments[1] == 'string' ? arguments[1] : 'vertical';
    
            if (typeof color === 'string' && color.match(/^gradient\((.*)\)$/i)) {

                // Allow for JSON gradients
                if (color.match(/^gradient\(({.*})\)$/i)) {
                    return RGraph.parseJSONGradient({object: this, def: RegExp.$1});
                }

                var parts = RegExp.$1.split(':');
    
                // Create the gradient
                if (dir == 'horizontal') {
                    var grad = this.context.createLinearGradient(0,0,this.canvas.width,0);
                } else {
                    var grad = this.context.createLinearGradient(0,this.canvas.height - prop.marginBottom,0,prop.marginTop);
                }
    
                var diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RGraph.trim(parts[0]));
    
                for (var j=1; j<parts.length; ++j) {
                    grad.addColorStop(
                        j * diff,
                        RGraph.trim(parts[j])
                    );
                }
            }
    
            return grad ? grad : color;
        };








        //
        // Sets the appropriate shadow
        //
        this.setShadow = function (i)
        {    
            if (prop.shadow) {
                //
                // Handle the appropriate shadow color. This now facilitates an array of differing
                // shadow colors
                //
                var shadowColor = prop.shadowColor;
        
                //
                // Accommodate an array of shadow colors as well as a single string
                //
                if (typeof shadowColor == 'object' && shadowColor[i - 1]) {
                    this.context.shadowColor = shadowColor[i];
    
                } else if (typeof shadowColor == 'object') {
                    this.context.shadowColor = shadowColor[0];
    
                } else if (typeof shadowColor == 'string') {
                    this.context.shadowColor = shadowColor;
                }
        
                this.context.shadowBlur    = prop.shadowBlur;
                this.context.shadowOffsetX = prop.shadowOffsetx;
                this.context.shadowOffsetY = prop.shadowOffsety;
            }
        };








        //
        // This function handles highlighting an entire data-series for the interactive
        // key
        // 
        // @param int index The index of the data series to be highlighted
        //
        this.interactiveKeyHighlight = function (index)
        {
            var coords = this.coords2[index];

            if (coords) {

                var pre_linewidth = this.context.lineWidth;
                var pre_linecap   = this.context.lineCap;
                
                this.context.lineWidth   = prop.linewidth + 10;
                this.context.lineCap     = 'round';
                this.context.strokeStyle = prop.keyInteractiveHighlightChartStroke;

                
                this.context.beginPath();
                if (prop.spline) {
                    this.drawSpline(this.context, coords, prop.keyInteractiveHighlightChart, null);
                } else {
                    for (var i=0,len=coords.length; i<len; i+=1) {
                        if (   i == 0
                            || RGraph.isNull(coords[i][1])
                            || (typeof coords[i - 1][1] != undefined && RGraph.isNull(coords[i - 1][1]))) {
                            this.context.moveTo(coords[i][0], coords[i][1]);
                        } else {
                            this.context.lineTo(coords[i][0], coords[i][1]);
                        }
                    }
                }
                this.context.stroke();
                
                // Reset the lineCap and lineWidth
                this.context.lineWidth = pre_linewidth;
                this.context.lineCap = pre_linecap;
            }
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
        // Draws error-bars for the Bar and Line charts
        //
        this.drawErrorbars = function ()
        {
            // Save the state of the canvas so that it can be restored at the end
            this.context.save();

                RGraph.noShadow(this);

                var coords = this.coords,
                         x = 0,
                 errorbars = prop.errorbars,
                    length = 0;

                // If not capped set the width of the cap to zero
                if (!prop.errorbarsCapped) {
                    prop.errorbarsCappedWidth = 0.001;
                    halfwidth = 0.0005;
                }

                // Set the linewidth
                this.context.lineWidth = prop.errorbarsLinewidth;
    
    
    
    
                for (var i=0; i<coords.length; ++i) {
                
                    var halfwidth = prop.errorbarsCappedWidth / 2 || 5,
                            color = prop.errorbarsColor || 'black';

                    // Set the perbar linewidth if the fourth option in the array
                    // is specified
                    if (errorbars[i] && typeof errorbars[i][3] === 'number') {
                        this.context.lineWidth = errorbars[i][3];
                    } else if (typeof prop.errorbarsLinewidth === 'number') {
                        this.context.lineWidth = prop.errorbarsLinewidth;
                    } else {
                        this.context.lineWidth = 1;
                    }

    
    
                    // Calulate the pixel size
                    if (typeof errorbars === 'number' || typeof errorbars[i] === 'number') {

                        if (typeof errorbars === 'number') {
                            var positiveLength = this.getYCoord(this.min) - this.getYCoord(this.min + errorbars),
                                negativeLength = positiveLength;
                        } else {
                            var positiveLength = this.getYCoord(this.min) - this.getYCoord(this.min + errorbars[i]),
                                negativeLength = positiveLength;
                        }

                        if (positiveLength || negativeLength) {

                            this.path(
                                'lj miter lc square b m % % l % % m % % l % % l % % m % % l % % s %',
                                coords[i][0] - halfwidth,coords[i][1] + negativeLength,
                                coords[i][0] + halfwidth,coords[i][1] + negativeLength,
                                coords[i][0],coords[i][1] + negativeLength,
                                coords[i][0],coords[i][1] - positiveLength,
                                coords[i][0] - halfwidth,coords[i][1] - positiveLength,
                                coords[i][0],coords[i][1] - positiveLength,
                                coords[i][0] + halfwidth,coords[i][1] - positiveLength,
                                color
                            );

                            this.path(
                                'lj miter lc square b m % % l % % s %',
                                coords[i][0] - halfwidth,coords[i][1] + negativeLength,
                                coords[i][0] + halfwidth,coords[i][1] + negativeLength,
                                color
                            );
                        }



                    } else if (typeof errorbars[i] === 'object' && !RGraph.isNull(errorbars[i])) {

                        var positiveLength = this.getYCoord(this.min) - this.getYCoord(this.min + errorbars[i][0]),
                            negativeLength = this.getYCoord(this.min) - this.getYCoord(this.min + errorbars[i][1]);


                        // Color
                        if (typeof errorbars[i][2] === 'string') {
                            color = errorbars[i][2];
                        }

                        // Cap width
                        halfwidth = typeof errorbars[i][4] === 'number' ? errorbars[i][4] / 2 : halfwidth;
    
    
                        // Set the linewidth
                        if (typeof errorbars[i] === 'object' && typeof errorbars[i][3] === 'number') {
                            this.context.lineWidth = errorbars[i][3];
                        } else if (typeof prop.errorbarsLinewidth === 'number') {
                            this.context.lineWidth = prop.errorbarsLinewidth;
                        } else {
                            this.context.lineWidth = 1;
                        }


                        if (!RGraph.isNull(errorbars[i][0])) {

                            this.path(
                                'lc square b  m % % l % % l % % m % % l % % s %',
                                coords[i][0],coords[i][1],
                                coords[i][0],coords[i][1] - positiveLength,
                                coords[i][0] - halfwidth,Math.round(coords[i][1] - positiveLength),
                                coords[i][0],Math.round(coords[i][1] - positiveLength),
                                coords[i][0] + halfwidth,Math.round(coords[i][1] - positiveLength),
                                color
                            );
                        }
    
                        if (typeof errorbars[i][1] === 'number') {

                            var negativeLength = Math.abs(this.getYCoord(errorbars[i][1]) - this.getYCoord(0));
    
                            this.path(
                                'b m % % l % % l % % m % % l % % s %',
                                coords[i][0],coords[i][1],
                                coords[i][0],coords[i][1] + negativeLength,
                                coords[i][0] - halfwidth,Math.round(coords[i][1] + negativeLength),
                                coords[i][0],Math.round(coords[i][1] + negativeLength),
                                coords[i][0] + halfwidth,Math.round(coords[i][1] + negativeLength),
                                color
                            );
                        }
                    }
                }

            this.context.restore();
        };








        //
        // Hides a line by setting the appropriate flag so that the .visible(index)
        // function returns the relevant result.
        // 
        // @param int index The index of the line to hide
        //
        this.hide = function ()
        {
            // Hide a single line
            if (typeof arguments[0] === 'number') {
                prop.lineVisible[arguments[0]] = false;
            
            // Hide multiple lines
            } else if (typeof arguments[0] === 'object') {
                for (var i=0; i<arguments[0].length; ++i) {
                    prop.lineVisible[arguments[0][i]] = false;
                }
                
            // Hide all lines
            } else {
                for (var i=0; i<this.original_data.length; ++i) {
                    prop.lineVisible[i] = false;
                }
            }
            
            RGraph.redraw();
            
            // Facilitate chaining
            return this;
        };








        //
        // Shows a line by setting the appropriate flag so that the .visible(index)
        // function returns the relevant result.
        // 
        // @param int index The index of the line to show
        //
        this.show = function ()
        {
            // Show a single line
            if (typeof arguments[0] === 'number') {
                prop.lineVisible[arguments[0]] = true;
            
            // Show multiple lines
            } else if (typeof arguments[0] === 'object') {
                for (var i=0; i<arguments[0].length; ++i) {
                    prop.lineVisible[arguments[0][i]] = true;
                }

            // Show all lines
            } else {
                for (var i=0; i<this.original_data.length; ++i) {
                    prop.lineVisible[i] = true;
                }
            }
            
            RGraph.redraw();            
            
            // Facilitate chaining
            return this;
        };








        //
        // Returns true/false as to wether a line is hidden or not
        // 
        // @param int index The index of the line to hide
        //
        this.hidden = function (index)
        {
            return !prop.lineVisible[index];
        };








        //
        // Unfold
        // 
        // This effect gradually increases the X/Y coordinatesfrom 0
        // 
        // @param object obj The chart object
        //
        this.unfold = function ()
        {
            var obj      = this,
                opt      = arguments[0] ? arguments[0] : {},
                frames   = opt.frames ? opt.frames : 30,
                frame    = 0,
                callback = arguments[1] ? arguments[1] : function () {},
                initial  = prop.animationUnfoldInitial;
            
            prop.animationFactor = prop.animationUnfoldInitial;

            function iterator ()
            {
                prop.animationFactor = ((1 - initial) * (frame / frames)) + initial;
    
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
        // Trace2
        // 
        // This is a new version of the Trace effect which no longer requires jQuery and is more compatible
        // with other effects (eg Expand). This new effect is considerably simpler and less code.
        // 
        // @param object     Options for the effect. Currently only "frames" is available.
        // @param int        A function that is called when the ffect is complete
        //
        this.trace = function ()
        {
            var obj       = this,
                callback  = arguments[2],
                opt       = arguments[0] || {},
                frames    = opt.frames || 30,
                frame     = 0,
                callback = arguments[1] || function () {};

            obj.set('animationTraceClip', 0);
    
            function iterator ()
            {
                RGraph.clear(obj.canvas);

                RGraph.redrawCanvas(obj.canvas);

                if (frame++ < frames) {
                    obj.set('animationTraceClip', frame / frames);
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    callback(obj);
                }
            }
            
            iterator();
            
            return this;
        };








        //
        // FoldToCenter
        // 
        // Line chart  FoldTocenter
        // 
        // @param object   OPTIONAL An object map of options
        // @param function OPTIONAL A callback to run when the effect is complete
        //
        this.foldtocenter = function ()
        {
            var obj      = this,
                opt      = arguments[0] || {},
                frames   = opt.frames || 30,
                frame    = 0,
                callback = arguments[1] || function () {},
                center_value = obj.scale2.max / 2;

            obj.set('yaxisScaleMax', obj.scale2.max);
            
            var original_data = RGraph.arrayClone(obj.original_data);
            
            function iterator ()
            {
                for (var i=0,len=obj.data.length; i<len; ++i) {
                    if (obj.data[i].length) {
                        for (var j=0,len2=obj.data[i].length; j<len2; ++j) {
                            
                            var dataset = obj.original_data[i];

                            if (dataset[j] > center_value) {
                                dataset[j] = original_data[i][j] - ((original_data[i][j] - center_value) * (frame / frames));
                            } else {
                                dataset[j] = original_data[i][j] + (((center_value - original_data[i][j]) / frames) * frame);
                            }
                        }
                    }
                }
                
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas)
    
                if (frame++ < frames) {
                    RGraph.Effects.updateCanvas(iterator);
                } else {
                    callback(obj);
                }
            }



            iterator();



            return this;
        };








        //
        // UnfoldFromCenterTrace effect
        // 
        // @param object   An object containing options
        // @param function A callback function
        //
        this.unfoldFromCenterTrace = function ()
        {
            var obj      = this,
                opt      = arguments[0] || {},
                frames   = opt.frames || 30,
                frame    = 0,
                data     = RGraph.arrayClone(obj.original_data),
                callback = arguments[1] || function () {};



            // Draw the chart once to get the scale values
            obj.canvas.style.visibility = 'hidden';
            obj.draw();

            var max = obj.scale2.max;

            RGraph.clear(obj.canvas);
            obj.canvas.style.visibility = 'visible';




            //
            // When the Trace function finishes it calls this function
            //
            var unfoldCallback = function ()
            {
                obj.original_data = data;
                obj.unfoldFromCenter({frames: frames / 2}, callback);
            };



            //
            // Determine the mid-point
            //
            var half = obj.get('xaxisPosition') == 'center' ? obj.min : ((obj.max - obj.min) / 2) + obj.min;
            obj.set('yaxisScaleMax', obj.max);
    
            for (var i=0,len=obj.original_data.length; i<len; ++i) {
                for (var j=0; j<obj.original_data[i].length; ++j) {
                    obj.original_data[i][j] = (obj.get('filled') && obj.get('filledAccumulative') && i > 0) ? 0 : half;
                }
            }

            RGraph.clear(obj.canvas);
            obj.trace({frames: frames / 2}, unfoldCallback);
            
            return obj;
        };








        //
        // UnfoldFromCenter
        // 
        // Line chart  unfold from center
        // 
        // @param object An option map of properties. Only frames is supported: {frames: 30}
        // @param function An optional callback
        //
        this.unfoldFromCenter = function ()
        {
            var obj           = this,
                opt           = arguments[0] || {},
                frames        = opt.frames || 30,
                frame         = 0,
                callback      = arguments[1] || function () {};
            
            // Draw the chart once to get the scale values
            obj.canvas.style.visibility = 'hidden';
            obj.draw();

            var max = obj.scale2.max;

            RGraph.clear(obj.canvas);
            obj.canvas.style.visibility = 'visible';

            var center_value  = obj.get('xaxisPosition') === 'center' ? prop.yaxisScaleMin : ((obj.max - obj.min) / 2) + obj.min,
                original_data = RGraph.arrayClone(obj.original_data),
                steps         = null;
            
            obj.set('yaxisScaleMax', max);

            if (!steps) {
            
                steps = [];
            
                for (var dataset=0,len=original_data.length; dataset<len; ++dataset) {
    
                    steps[dataset] = []
    
                    for (var i=0,len2=original_data[dataset].length; i<len2; ++i) {
                        if (prop.filled && prop.filledAccumulative && dataset > 0) {
                            steps[dataset][i] = original_data[dataset][i] / frames;
                            obj.original_data[dataset][i] = center_value;
                        } else {
                            steps[dataset][i] = (original_data[dataset][i] - center_value) / frames;
                            obj.original_data[dataset][i] = center_value;
                        }
                    }
                }
            }

            function unfoldFromCenter ()
            {
                for (var dataset=0; dataset<original_data.length; ++dataset) {
                    for (var i=0; i<original_data[dataset].length; ++i) {
                        obj.original_data[dataset][i] += steps[dataset][i];
                    }
                }

                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);
    
                if (--frames > 0) {
                    RGraph.Effects.updateCanvas(unfoldFromCenter);
                } else {
                    obj.original_data = RGraph.arrayClone(original_data);
                    RGraph.clear(obj.canvas);
                    RGraph.redrawCanvas(obj.canvas);

                    callback(obj);
                }
            }
            
            unfoldFromCenter();
            
            return this;
        };








        //
        // Determines whether a point is adjustable or not.
        //
        // @param object A shape object
        //
        this.isAdjustable = function (shape)
        {
            if (RGraph.isNull(prop.adjustableOnly)) {
                return true;
            }

            if (RGraph.isArray(prop.adjustableOnly) && prop.adjustableOnly[shape.sequentialIndex]) {
                return true;
            }

            return false;
        };








        //
        // A worker function that handles Bar chart specific tooltip substitutions
        //
        this.tooltipSubstitutions = function (opt)
        {
            var indexes = RGraph.sequentialIndexToGrouped(opt.index, this.data);

            //
            //// Dataset tooltips
            ////
            if (prop.tooltipsDataset) {
                
                return {
                    dataset: indexes[1],
                    index:   indexes[0],
          sequentialIndex:   opt.index,
                   values:   this.data[indexes[1]]
                };
            
            //
            // Regular tooltips
            //
            } else {
                
                // Create the values array which contains each datasets value
                for (var i=0,values=[]; i<this.original_data.length; ++i) {
                    values.push(this.original_data[i][indexes[1]]);
                }
    
                return {
                      index: indexes[1],
                    dataset: indexes[0],
            sequentialIndex: opt.index,
                      value: this.data_arr[opt.index],
                     values: values
                };
            }
        };








        //
        // A worker function that returns the correct color/label/value
        //
        // @param object specific The indexes that are applicable
        // @param number index    The appropriate index
        //
        this.tooltipsFormattedCustom = function (specific, index)
        {
            return {
            };
        };








        // Adds dataset tooltips
        this.addDatasetTooltip = function ()
        {
            var obj = this;

            //
            // This is the function that handles dataset tooltips
            //
            // TODO Needs highlighting adding
            //
            this.datasetTooltipsListener = function (e)
            {
                var mouseXY = RGraph.getMouseXY(e);
                
                // This defaults the tooltipsDatasetEvent property to mousemove
                if (!obj.properties.tooltipsDatasetEvent) {
                    obj.properties.tooltipsDatasetEvent = 'click';
                }
    
                //
                // Determine the correct coords array to use
                //
                if (obj.properties.spline) {
                    var coords = obj.coordsSpline;
                } else {
                    var coords = obj.coords2;
                }
    
                for (var i=0; i<coords.length; ++i) {

                    // Start a path and draw the line so it can be tested for clicks
                    // (this is not highlighting the line)
                    var path = 'b lc round lw 10 m {1} {2}'.format(
                        coords[i][0][0],
                        coords[i][0][1]
                    );

                    for (var j=0; j<coords[i].length; ++j) {
                        path += ' l {1} {2}'.format(
                            coords[i][j][0],
                            coords[i][j][1]
                        );
                    }
                
                    // Finish the path
                    //path += ' s red'
                    
                    // Stroke it to the canvas
                    obj.path(path);
                
                    // Now test it
                    if (obj.context.isPointInStroke(mouseXY[0], mouseXY[1])) {

                        var over = true;
    
                        // Show the tooltip if we're in the click handler or change the
                        // pointer if we're in the mousemove listener.
                        if (    e.type === 'click'
                            || (e.type === 'mousemove' && obj.properties.tooltipsDatasetEvent === 'mousemove' && (!RGraph.Registry.get('tooltip') || i != RGraph.Registry.get('tooltip').__dataset__))
                           ) {
                            
                            RGraph.hideTooltip();
                            RGraph.redraw();

                            RGraph.tooltip({
                                object: obj,
                                text: typeof obj.properties.tooltipsDataset === 'string'
                                          ? obj.properties.tooltipsDataset
                                          : obj.properties.tooltipsDataset[i],
                                x: mouseXY[0],
                                y: mouseXY[1],
                                index: i,
                                event: e
                            });
                            
                            RGraph.Registry.get('tooltip').__index__   = 0;
                            RGraph.Registry.get('tooltip').__index2__  = 0;
                            RGraph.Registry.get('tooltip').__dataset__ = i;
                            
                            // Highlight the dataset.
                            // Start a path and redraw the line
                            var path = 'b lw %1 m %2 %3'.format(
                                prop.linewidth + 10,
                                coords[i][0][0],
                                coords[i][0][1]
                            );

                            for (var j=0; j<coords[i].length; ++j) {
                                path += ' l {1} {2}'.format(
                                    coords[i][j][0],
                                    coords[i][j][1]
                                );
                            }
                            
                            // Finish the path
                            path += ' ga 0.2 s ' + prop.colors[i] + ' ga 1';
                            obj.path(path);
                        }
                        
                        if (e.type === 'mousemove') {
                            e.target.style.cursor = 'pointer';
                        }
                    }
                    
                    if (!over) {
                        // Hide the tooltip if the event is click
                        if (e.type === 'click') {
                            RGraph.hideTooltip();
                            RGraph.redraw();
                        }
    
                        // Reset the cursor type
                        obj.canvas.style.cursor = 'default';
                    }
                }
                
                e.stopPropagation();
            };
            
            if (!this.datasetTooltipsListenerAdded) {
                this.canvas.addEventListener('click', this.datasetTooltipsListener, false);
                this.canvas.addEventListener('mousemove', this.datasetTooltipsListener, false);
                window.addEventListener('click', function (e)
                {
                    RGraph.redraw();
                }, false);
                
                this.datasetTooltipsListenerAdded = true;
            }
        };








        //
        // Register the object so it is redrawn when necessary
        //
        RGraph.register(this);








        //
        // Allow all lines to start off as visible
        //
        for (var i=0; i<this.original_data.length; ++i) {
            prop.lineVisible[i] = true;
        }

        //
        // This is the 'end' of the constructor so if the first argument
        // contains configuration data - handle that.
        //
        RGraph.parseObjectStyleConfig(this, conf.options);
    };