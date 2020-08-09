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
    // The bar chart constructor
    //
    RGraph.Waterfall = function (conf)
    {
        this.id                = conf.id;
        this.canvas            = document.getElementById(this.id);
        this.context           = this.canvas.getContext ? this.canvas.getContext("2d") : null;
        this.canvas.__object__ = this;
        this.type              = 'waterfall';
        this.max               = 0;
        this.data              = conf.data;
        this.isRGraph          = true;
        this.isrgraph          = true;
        this.rgraph            = true;
        this.coords            = [];
        this.uid               = RGraph.createUID();
        this.canvas.uid        = this.canvas.uid ? this.canvas.uid : RGraph.createUID();
        this.colorsParsed      = false;
        this.coordsText        = [];
        this.original_colors   = [];
        this.firstDraw         = true; // After the first draw this will be false






        // Various config
        this.properties =
        {
            backgroundBarsCount:               null,
            backgroundBarsColor1:              'rgba(0,0,0,0)',
            backgroundBarsColor2:              'rgba(0,0,0,0)',
            backgroundGrid:                    true,
            backgroundGridAutofit:             true,
            backgroundGridAutofitAlign:        true,
            backgroundGridColor:               '#ddd',
            backgroundGridLinewidth:           1,
            backgroundGridHsize:               20,
            backgroundGridVsize:               20,
            backgroundGridVlines:              true,
            backgroundGridHlines:              true,
            backgroundGridBorder:              true,
            backgroundGridAlign:               true,
            backgroundGridHlinesCount:         5,
            backgroundGridVlinesCount:         20,
            backgroundImage:                   null,
            backgroundImageStretch:            true,
            backgroundImageX:                  null,
            backgroundImageY:                  null,
            backgroundImageW:                  null,
            backgroundImageH:                  null,
            backgroundImageAlign:              null,
            backgroundHbars:                   null,

            linewidth:                         1,

            colorsStroke:                      '#666',
            colors:                            ['green','red','blue'],
            colorsSequential:                  false,

            marginLeft:                        35,
            marginRight:                       35,
            marginTop:                         35,
            marginBottom:                      35,
            marginInner:                       5,

            xaxis:                   true,
            xaxisPosition:           'bottom',
            xaxisLinewidth:          1,
            xaxisColor:              'black',
            xaxisTickmarks:          true,
            xaxisTickmarksLength:    3,
            xaxisTickmarksLastLeft:  null,
            xaxisTickmarksLastRight: null,
            xaxisTickmarksCount:     null,
            xaxisLabels:             null,            
            xaxisLabelsSize:         null,
            xaxisLabelsFont:         null,
            xaxisLabelsItalic:       null,
            xaxisLabelsBold:         null,
            xaxisLabelsColor:        null,
            xaxisLabelsOffsetx:      0,
            xaxisLabelsOffsety:      0,
            xaxisLabelsHalign:       null,
            xaxisLabelsValign:       null,
            xaxisLabelsPosition:     'section',
            xaxisLabelsSpecificAlign:'LEFT',
            xaxisPosition:           'bottom',
            xaxisLabelsAngle:        0,
            xaxisTitle:              '',
            xaxisTitleBold:          null,
            xaxisTitleSize:          null,
            xaxisTitleFont:          null,
            xaxisTitleColor:         null,
            xaxisTitleItalic:        null,
            xaxisTitlePos:           null,
            xaxisTitleOffsetx:       0,
            xaxisTitleOffsety:       0,
            xaxisTitleX:             null,
            xaxisTitleY:             null,
            xaxisTitleHalign:        'center',
            xaxisTitleValign:        'top',


            yaxis:                    true,
            yaxisPosition:            'left',
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

            yaxisTitle:                        '',
            yaxisTitleBold:                    null,
            yaxisTitleItalic:                  null,
            yaxisTitleSize:                    null,
            yaxisTitleFont:                    null,
            yaxisTitleColor:                   null,
            yaxisTitlePos:                     null,
            yaxisTitleAlign:                   'left',
            yaxisTitleX:                       null,
            yaxisTitleY:                       null,
            yaxisLabels:                       true,
            yaxisLabelsCount:                  5,
            yaxisLabelsOffsetx:                0,
            yaxisLabelsOffsety:                0,
            yaxisLabelsFont:                   null,
            yaxisLabelsSize:                   null,
            yaxisLabelsColor:                  null,
            yaxisLabelsBold:                   null,
            yaxisLabelsItalic:                 null,
            yaxisScaleMax:                     null,
            yaxisScaleMin:                     0,
            yaxisScaleUnitsPre:                '',
            yaxisScaleUnitsPost:               '',
            yaxisScaleDecimals:                0,
            yaxisScalePoint:                   '.',
            yaxisScaleThousand:                ',',
            yaxisScaleFormatter:               null,

            labelsAbove:                       false,
            labelsAboveFont:                   null,
            labelsAboveSize:                   null,
            labelsAboveBold:                   null,
            labelsAboveItalic:                 null,
            labelsAboveColor:                  null,
            labelsAboveOffsetx:                0,
            labelsAboveOffsety:                0,
            labelsAboveSpecific:               null,
            labelsAboveDecimals:               0,
            labelsAboveUnitsPre:               '',
            labelsAboveUnitsPost:              '',
            labelsAbovePoint:                  '.',
            labelsAboveThousand:               ',',
            labelsAboveFormatter:              null,
            labelsAboveTotalItalic:            null,
            labelsAboveTotalBold:              null,
            labelsAboveTotalSize:              null,
            labelsAboveTotalFont:              null,
            labelsAboveTotalColor:             null,
            labelsAboveTotalDecimals:          null,
            labelsAboveTotalUnitsPre:          null,
            labelsAboveTotalUnitsPost:         null,
            labelsAboveTotalPoint:             null,
            labelsAboveTotalThousand:          null,
            labelsAboveTotalFormatter:         null,

            textColor:                         'black',
            textSize:                          12,
            textFont:                          'Arial, Verdana, sans-serif',
            textBold:                          false,
            textItalic:                        false,
            textAccessible:                    true,
            textAccessibleOverflow:            'visible',
            textAccessiblePointerevents:       false,

            title:                             '',
            titleColor:                        'black',
            titleBackground:                   null,
            titleHpos:                         null,
            titleVpos:                         null,
            titleBold:                         null,
            titleFont:                         null,
            titleSize:                         null,
            titleItalic:                       null,
            titleColor:                        null,
            titleX:                            null,
            titleY:                            null,
            titleHalign:                       null,
            titleValign:                       null,

            shadow:                            false,
            shadowColor:                       '#666',
            shadowOffsetx:                     3,
            shadowOffsety:                     3,
            shadowBlur:                        3,

            tooltips:                          null,
            tooltipsEffect:                    'fade',
            tooltipsCssClass:                  'RGraph_tooltip',
            tooltipsCss:                       null,
            tooltipsEvent:                     'onclick',
            tooltipsHighlight:                 true,
            tooltipsOverride:                  null,
            tooltipsFormattedThousand:         ',',
            tooltipsFormattedPoint:            '.',
            tooltipsFormattedDecimals:         0,
            tooltipsFormattedUnitsPre:         '',
            tooltipsFormattedUnitsPost:        '',
            tooltipsFormattedKeyColors:        null,
            tooltipsFormattedKeyColorsShape: 'square',
            tooltipsFormattedKeyLabels:        [],

            highlightStroke:                   'rgba(0,0,0,0)',
            highlightFill:                     'rgba(255,255,255,0.7)',

            contextmenu:                       null,

            crosshairs:                        false,
            crosshairsColor:                   '#333',
            crosshairsHline:                   true,
            crosshairsVline:                   true,

            annotatable:                       false,
            annotatableLinewidth:              1,
            annotatableColor:                  'black',

            resizable:                         false,
            resizableHandleBackground:         null,

            total:                             true,

            multiplierX:                       1, // Used for animation
            multiplierW:                       1, // Used for animation

            key:                               null,
            keyBackground:                     'white',
            keyPosition:                       'graph',
            keyHalign:                         'right',
            keyShadow:                         false,
            keyShadowColor:                    '#666',
            keyShadowBlur:                     3,
            keyShadowOffsetx:                  2,
            keyShadowOffsety:                  2,
            keyPositionGutterBoxed:            false,
            keyPositionX:                      null,
            keyPositionY:                      null,
            keyColorShape:                     'square',
            keyRounded:                        true,
            keyLinewidth:                      1,
            keyColors:                         null,
            keyInteractive:                    false,
            keyInteractiveHighlightChartStroke:'#000',
            keyInteractiveHighlightChartFill:  'rgba(255,255,255,0.7)',
            keyInteractiveHighlightLabel:      'rgba(255,0,0,0.2)',
            keyLabelsColor:                    null,
            keyLabelsFont:                     null,
            keyLabelsSize:                     null,
            keyLabelsBold:                     null,
            keyLabelsItalic:                   null,
            keyLabelsOffsetx:                  0,
            keyLabelsOffsety:                  0,

            barOffsetx:                        0, // Used to facilitate multiple dataset Waterfall charts
            barOffsety:                        0, // Used to facilitate multiple dataset Waterfall charts

            clearto:                           'rgba(0,0,0,0)'
        }

        // Check for support
        if (!this.canvas) {
            alert('[WATERFALL] No canvas support');
            return;
        }
        
        //
        // Create the $ objects
        // 
        // 2/5/016: Now also use this loop to go through the dat conerting
        // strings to floats
        //
        for (var i=0,len=this.data.length; i<=len; ++i) {
            
            // Create the object for adding event listeners
            this['$' + i] = {}
            
            // Ensure that the data point is numeric
            if (typeof this.data[i] === 'string') {
                this.data[i] = parseFloat(this.data[i]);
            }
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
        // A setter
        // 
        // @param name  string The name of the property to set
        // @param value mixed  The value of the property
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
        // A getter
        // 
        // @param name  string The name of the property to get
        //
        this.get = function (name)
        {
            return prop[name];
        };








        //
        // The function you call to draw the bar chart
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
            // Draw the background image
            //
            RGraph.drawBackgroundImage(this);



            //
            // Make the margins easy ro access
            //            
            this.marginLeft   = prop.marginLeft;
            this.marginRight  = prop.marginRight;
            this.marginTop    = prop.marginTop;
            this.marginBottom = prop.marginBottom;

            //
            // Stop the coords array from growing uncontrollably
            //
            this.coords = [];



            //
            // Stop this growing uncontrollably
            //
            this.coordsText = [];




            //
            // This gets used a lot
            //
            this.centery = ((this.canvas.height - this.marginTop - this.marginBottom) / 2) + this.marginTop;

            //
            // Work out a few things. They need to be here because they depend on things you can change after you instantiate the object
            //
            this.max            = 0;
            this.grapharea      = this.canvas.height - this.marginTop - this.marginBottom;
            this.graphwidth     = this.canvas.width - this.marginLeft - this.marginRight;
            this.halfTextHeight = prop.textSize / 2;
    
    
            //
            // Work out the maximum value
            //
            this.max     = this.getMax(this.data);
            var decimals = prop.yaxisScaleDecimals;

            this.scale2 = RGraph.getScale({object: this, options: {
                'scale.max':          typeof prop.yaxisScaleMax == 'number' ? prop.yaxisScaleMax : this.max,
                'scale.min':          prop.yaxisScaleMin,
                'scale.strict':       typeof prop.yaxisScaleMax === 'number' ? true : false,
                'scale.decimals':     Number(decimals),
                'scale.point':        prop.yaxisScalePoint,
                'scale.thousand':     prop.yaxisScaleThousand,
                'scale.round':        prop.yaxisScaleRound,
                'scale.units.pre':    prop.yaxisScaleUnitsPre,
                'scale.units.post':   prop.yaxisScaleUnitsPost,
                'scale.labels.count': prop.yaxisLabelsCount,
                'scale.formatter':   prop.yaxisScaleFormatter
            }});

            this.max = this.scale2.max;
            this.min = this.scale2.min;
    
            // Draw the background hbars
            RGraph.drawBars(this)

            // Progressively Draw the chart
            RGraph.Background.draw(this);
    
            this.drawAxes();
            this.drawBars();
            this.drawLabels();
            
//
// If the X axis is at the bottom AND ymin is 0 - draw the it
// again so that it appears "on top" of the bars
//
//if (   prop.xaxisPosition === 'bottom'
//    && prop.axes
//    && prop.xaxis
//    && prop.yaxisScaleMin === 0) {

//    this.context.strokeStyle = prop.axesColor;
//    this.context.strokeRect(
//        prop.marginLeft,
//        this.canvas.height - this.marginBottom,
//        this.canvas.width - this.marginLeft - this.marginRight,
//        0
//    );
//}
    
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
            // This installs the event listeners
            //
            RGraph.installEventListeners(this);
            
            
            // Draw a key if necessary
            if (prop.key && prop.key.length) {
                RGraph.drawKey(this, prop.key, prop.colors);
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
        // Draws the charts axes
        //
        this.drawAxes = function ()
        {
            //
            // Draw the X axis
            //
            RGraph.drawXAxis(this);

            //
            // Draw the Y axis
            //
            RGraph.drawYAxis(this);
        };








        //
        // Draws the labels for the graph
        //
        this.drawLabels = function ()
        {
            //
            // Draw the labelsAbove labels
            //
            if (prop.labelsAbove) {
                this.drawLabelsAbove();
            }
        };








        //
        // This function draws all of the above labels
        //
        this.drawLabelsAbove = function ()
        {
            var data      = this.data,
                unitsPre  = prop.labelsAboveUnitsPre,
                unitsPost = prop.labelsAboveUnitsPost,
                decimals  = prop.labelsAboveDecimals,
                thousand  = prop.labelsAboveThousand,
                point     = prop.labelsAbovePoint,
                formatter = prop.labelsAboveFormatter;

            var textConf = RGraph.getTextConf({
                object: this,
                prefix: 'labelsAbove'
            });

            for (var i=0; i<this.data.length + (prop.total ? 1 : 0); ++i) {

                // Is this the "total" column
                if (prop.total && i === this.data.length) {
                    var isTotal = true;
                }
                
                // Get the value
                var value = Number(isTotal ? this.total : this.data[i]);
                
                // Determine the color based on whether the value is positive,
                // negative or the total
                if (typeof prop.labelsAboveColor === 'object' && prop.labelsAboveColor) {
                    if (isTotal && typeof prop.labelsAboveColor[2] === 'string') {
                        color = prop.labelsAboveColor[2];
                    } else if (this.data[i] < 0) {
                        color = prop.labelsAboveColor[1];
                    } else {
                        color = prop.labelsAboveColor[0];
                    }
                }
                
                
                // Do the color handling again if this is the last
                // label (and its an object) but using the
                // labelsAboveLastColor property if it's set
                if (typeof prop.labelsAboveTotalColor === 'object' && prop.labelsAboveTotalColor) {
                    if (   isTotal
                        && typeof prop.labelsAboveTotalColor[0] === 'string'
                        && typeof prop.labelsAboveTotalColor[1] === 'string'
                        ) {

                        if (this.total < 0) {
                            color = prop.labelsAboveTotalColor[1];
                        } else {
                            color = prop.labelsAboveTotalColor[0];
                        }
                    }
                }

                var coords = this.coords[i];




                // This code is repeated below for the last label. Temporarily
                // set the point and thousand properies because the numberFormat
                // function is dumb. These properties are reset after the last
                // label has been formatted
                var tmpScaleThousand = prop.yaxisScaleThousand,
                    tmpScalePoint    = prop.yaxisScaleDecimal;

                prop.yaxisScaleThousand = prop.labelsAboveThousand;
                prop.yaxisScalePoint    = prop.labelsAbovePoint;

                // Custom formatting or use the numberFormat function
                if (formatter) {
                    var str = (formatter)({
                        object: this,
                        value: value,
                        index: i
                    });
                } else {
                    var str = RGraph.numberFormat({
                        object:    this,
                        number:    String(value.toFixed(decimals)),
                        unitspre:  unitsPre,
                        unitspost: unitsPost,
                        point:     point,
                        thousand:  thousand
                    });
                }








                // Allow for the styling of the last label
                if (isTotal || i === this.data.length) {

                    if (typeof prop.labelsAboveTotalFont       === 'string')    textConf.font   = prop.labelsAboveTotalFont;
                    if (typeof prop.labelsAboveTotalColor      === 'string')    textConf.color  = prop.labelsAboveTotalColor;
                    if (typeof prop.labelsAboveTotalSize       === 'number')    textConf.size   = prop.labelsAboveTotalSize;
                    if (!RGraph.isNull(prop.labelsAboveTotalBold))                  textConf.bold   = prop.labelsAboveTotalBold;
                    if (!RGraph.isNull(prop.labelsAboveTotalItalic))                textConf.italic = prop.labelsAboveTotalItalic;
                    if (typeof prop.labelsAboveTotalUnitsPre  === 'string')    unitsPre        = prop.labelsAboveTotalUnitsPre;
                    if (typeof prop.labelsAboveTotalUnitsPost === 'string')    unitsPost       = prop.labelsAboveTotalUnitsPost;
                    if (typeof prop.labelsAboveTotalDecimals   === 'number')    decimals        = prop.labelsAboveTotalDecimals;
                    if (typeof prop.labelsAboveTotalFormatter  === 'function')  formatter       = prop.labelsAboveTotalFormatter;
                    if (typeof prop.labelsAboveTotalThousand   === 'string')    thousand        = prop.labelsAboveTotalThousand;
                    if (typeof prop.labelsAboveTotalPoint      === 'string')    point           = prop.labelsAboveTotalPoint;




                    // Custom formatting or use the numberFormat function
                    // This code is repeated just up above
                    if (formatter) {
                        var str = (formatter)({
                            object: this,
                            value: value,
                            index: i
                        });
                    } else {

                        str = RGraph.numberFormat({
                            object:    this,
                            number:    String(value.toFixed(decimals)),
                            unitspre:  unitsPre,
                            unitspost: unitsPost,
                            point:     point,
                            thousand:  thousand
                        });
                    }



                    // These two variables can now be reset to what they were when we
                    // started
                    prop.yaxisScaleThousand = tmpScaleThousand;
                    prop.yaxisScalePoint    = tmpScalePoint;
                }

                // Allow for specific labels
                if (   typeof prop.labelsAboveSpecific === 'object'
                    && !RGraph.isNull(prop.labelsAboveSpecific)
                   ) {
                   
                   if ( typeof prop.labelsAboveSpecific[i] === 'string' || typeof prop.labelsAboveSpecific[i] === 'number' ) {
                       str = prop.labelsAboveSpecific[i];
                   } else {
                       str = '';
                   }
                }


                RGraph.text({
                            
               object: this,

                 font: textConf.font,
                 size: textConf.size,
                color: textConf.color,
                 bold: textConf.bold,
               italic: textConf.italic,

                    x:      coords[0] + (coords[2] / 2) + prop.labelsAboveOffsetx,
                    y:      (isTotal ? this.total : this.data[i]) >= 0 ? (coords[1] - 3 - prop.labelsAboveOffsety) : (coords[1] + coords[3] + 3 + prop.labelsAboveOffsety),
                    text:   str,
                    valign: (isTotal ? this.total : this.data[i]) >= 0 ? 'bottom' : 'top',
                    halign: 'center',
                    tag:    'labels.above'
                });
            }
        };








        //
        // Draws the bars on to the chart
        //
        this.drawBars = function ()
        {
            var context      = this.context,
                canvas       = this.canvas,
                hmargin      = prop.marginInner,
                runningTotal = 0;
    
            this.context.lineWidth = prop.linewidth + 0.001;

            for (var i=0,len=this.data.length,seq=0; i<len; ++i,++seq) {

                this.context.beginPath();
                    
                    this.context.strokeStyle = prop.colorsStroke;

                    var x = Math.round( this.marginLeft + hmargin + (((this.graphwidth / (this.data.length + (prop.total ? 1 : 0))) * i) * prop.multiplierX));
                    
                    // Must be before the y coord calculation
                    var h  = this.getYCoord(0) - this.getYCoord(Math.abs(this.data[i]));

                    
                    
                    // Work out the Y coordinate
                    if (i === 0) {
                        y = this.getYCoord(0) - h;
                    } else {
                        y = this.getYCoord(runningTotal) - h;
                    }
                    y = Math.round(y);
                    




                    var w = ((this.canvas.width - this.marginLeft - this.marginRight) / (this.data.length + (prop.total ? 1 : 0 )) ) - (2 * prop.marginInner);
                        w = w * prop.multiplierW;


                    // Adjust the coords for negative values
                    if (this.data[i] < 0) {
                        y += h;
                    }

                    
                    // Allow for sequential colors
                    if (prop.colorsSequential) {
                        this.context.fillStyle = prop.colors[seq];
                    } else {
                        // Color
                        this.context.fillStyle = this.data[i] >= 0 ? prop.colors[0] : prop.colors[1];
                    }

                    
                    if (prop.shadow) {
                        RGraph.setShadow({
                            object: this,
                            prefix: 'shadow'
                        });
                    } else {
                        RGraph.noShadow(this);
                    }



                    //
                    // Draw the bar, first accounting for negative heights
                    //
                    if (h < 0) {
                        h = Math.abs(h);
                        y = y - h;
                    }

                    this.context.rect(
                        x + prop.barOffsetx,
                        Math.floor(y) + prop.barOffsety,
                        w,
                        Math.floor(h)
                    );

                    this.coords.push([x, y, w, h]);
                    


                    runningTotal += this.data[i];

                this.context.stroke();
                this.context.fill();
            }

            // Store the total
            this.total = runningTotal;

            if (prop.total) {

                // This is the height of the final bar
                if (prop.xaxisPosition === 'top') {
                    h = this.getYCoord(Math.abs(runningTotal)) - this.getYCoord(0);
                } else {
                    h = this.getYCoord(0) - this.getYCoord(Math.abs(runningTotal));
                }

                // Set the Y (ie the start point) value
                if (prop.xaxisPosition == 'center') {
                    y = runningTotal > 0 ? this.getYCoord(0) - h : this.getYCoord(0);
                
                } else if (prop.xaxisPosition == 'top') {
                    y = this.getYCoord(0);
                
                } else {
                    if (runningTotal > 0) {
                        y = this.getYCoord(0) - h;
                    } else {
                        y = this.getYCoord(0);
                    }
                }
            
                // This is the X position of the final bar
                x = x + (prop.marginInner * 2) + w;
            
                
                // Allow for sequential colors
                if (prop.colorsSequential) {
                    this.context.fillStyle = prop.colors[seq]
                } else {
                    // Final color
                    this.context.fillStyle = prop.colors[2];
                }

                this.path(
                    'b r % % % % s % f %',
                    x + prop.barOffsetx, y + prop.barOffsety, w, h,
                    this.context.strokeStyle,this.context.fillStyle
                );

                // This is set so that the next iteration of the loop will be able to
                // access THIS iterations coordinates
                var previousCoords = [x, y, w, Math.abs(h)];

                // Add the coordinates to the coords array (the previousCooords array, at
                // this point, is actually THIS iterations coords 
                this.coords.push(previousCoords);
            }





            // Turn off the shadow
            RGraph.noShadow(this);






            //
            // This draws the connecting lines
            //
            this.context.lineWidth   = 1;
            this.context.strokeStyle = '#666';
            
            this.context.beginPath();

            for (var i=1,len=this.coords.length; i<len; i+=1) {

                var prev     = this.coords[i - 1],
                    curr     = this.coords[i],
                    prevData = this.data[i-1];

                // CANNOT be a part of the var chain above
                if (prop.xaxisPosition === 'top') {
                    var y = (prevData > 0 ? prev[1] + prev[3] : prev[1]);
                } else {
                    var y = (prevData > 0 ? prev[1] : prev[1] + prev[3]);
                }


                this.context.moveTo(
                    prev[0] + prev[2] + prop.barOffsetx,
                    y + prop.barOffsety
                );

                this.context.lineTo(
                    curr[0] + prop.barOffsetx,
                    y + prop.barOffsety
                );

            }
            
            this.context.stroke();
        };








        //
        // Not used by the class during creating the graph, but is used by event handlers
        // to get the coordinates (if any) of the selected bar
        // 
        // @param object e The event object
        //
        this.getShape = function (e)
        {
            //
            // Loop through the bars determining if the mouse is over a bar
            //
            for (var i=0,len=this.coords.length; i<len; i++) {
    
                var mouseXY = RGraph.getMouseXY(e),
                    mouseX  = mouseXY[0],
                    mouseY  = mouseXY[1];
    
                var left   = this.coords[i][0],
                    top    = this.coords[i][1],
                    width  = this.coords[i][2],
                    height = this.coords[i][3];
    
                if (   mouseX >= left
                    && mouseX <= (left + width)
                    && mouseY >= top
                    && mouseY <= top + height) {
                    
                    var tooltip = RGraph.parseTooltipText ? RGraph.parseTooltipText(prop.tooltips, i) : null;
    
                    return {
                         object: this,
                              x: left,
                              y: top,
                          width: width,
                         height: height,
                          index: 0,
                        dataset: i,
                sequentialIndex: i,
                          label: prop.xaxisLabels && typeof prop.xaxisLabels[i] === 'string' ? prop.xaxisLabels[i] : null,
                        tooltip: typeof tooltip === 'string' ? tooltip : null
                    };
                }
            }
            
            return null;
        };








        //
        // The Waterfall is slightly different to Bar/Line charts so has this function to get the max value
        //
        this.getMax = function (data)
        {
            var runningTotal = 0, max = 0;
    
            for (var i=0,len=data.length; i<len; i+=1) {
                runningTotal += data[i];
                
                max = Math.max(Math.abs(runningTotal), max);
            }

            return Math.abs(max);
        };








        //
        // This function facilitates the installation of tooltip event
        // listeners if tooltips are defined.
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
            if (typeof prop.highlightStyle === 'function') {
                (prop.highlightStyle)(shape);
            
            // Highlight all of the rects except this one - essentially an inverted highlight
            } else if (typeof prop.highlightStyle === 'string' && prop.highlightStyle === 'invert') {
                for (var i=0; i<this.coords.length; ++i) {
                    if (i !== shape.sequentialIndex) {
                        this.path(
                            'b r % % % % s % f %',
                            this.coords[i][0],this.coords[i][1],this.coords[i][2],this.coords[i][3],
                            prop.highlightStroke,
                            prop.highlightFill
                        );
                    }
                }
            } else {
                RGraph.Highlight.rect(this, shape);
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
    
            if (
                   mouseXY[0] > this.marginLeft
                && mouseXY[0] < (this.canvas.width - this.marginRight)
                && mouseXY[1] > this.marginTop
                && mouseXY[1] < (this.canvas.height - this.marginBottom)
                ) {

                return this;
            }
        };








        //
        // This method returns the appropriate Y coord for the given value
        // 
        // @param number value The value
        //
        this.getYCoord = function (value)
        {
            // X axis position in the center
            if (prop.xaxisPosition == 'center') {

                if (value < (-1 * this.max)) {
                    return null;
                }
            
                var coord = (value / this.max) * (this.grapharea / 2);    
                return this.marginTop + (this.grapharea / 2) - coord;




            // X axis position at the top
            } else if (prop.xaxisPosition == 'top') {

                if (value < 0) return null;
            
                var coord = (value / this.max) * this.grapharea;    
                return this.marginTop + coord;





            } else {

                var coord = ( (value - this.scale2.min) / (this.max - this.scale2.min) ) * this.grapharea;
                    coord = coord + this.marginBottom;

                return this.canvas.height - coord;
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
                this.original_colors.keyColors             = RGraph.arrayClone(prop.keyColors);
                this.original_colors.crosshairsColor       = RGraph.arrayClone(prop.crosshairsColor);
                this.original_colors.highlightStroke       = RGraph.arrayClone(prop.highlightStroke);
                this.original_colors.highlightFill         = RGraph.arrayClone(prop.highlightFill);
                this.original_colors.backgroundBarsColor1  = RGraph.arrayClone(prop.backgroundBarsColor1);
                this.original_colors.backgroundBarsColor2  = RGraph.arrayClone(prop.backgroundBarsColor2);
                this.original_colors.backgroundGridColor   = RGraph.arrayClone(prop.backgroundGridColor);
                this.original_colors.colorsStroke          = RGraph.arrayClone(prop.colorsStroke);
                this.original_colors.xaxisColor              = RGraph.arrayClone(prop.xaxisColor);
                this.original_colors.yaxisColor              = RGraph.arrayClone(prop.yaxisColor);
            }


            // Colors
            var colors = prop.colors;

            if (colors) {
                for (var i=0,len=colors.length; i<len; ++i) {
                    colors[i] = this.parseSingleColorForGradient(colors[i]);
                }
            }
    
            // keyColors
            var colors = prop.keyColors;

            if (colors) {
                for (var i=0,len=colors.length; i<len; ++i) {
                    colors[i] = this.parseSingleColorForGradient(colors[i]);
                }
            }
    
             prop.crosshairsColor        = this.parseSingleColorForGradient(prop.crosshairsColor);
             prop.highlightStroke        = this.parseSingleColorForGradient(prop.highlightStroke);
             prop.highlightFill          = this.parseSingleColorForGradient(prop.highlightFill);
             prop.backgroundBarsColor1  = this.parseSingleColorForGradient(prop.backgroundBarsColor1);
             prop.backgroundBarsColor2  = this.parseSingleColorForGradient(prop.backgroundBarsColor2);
             prop.backgroundGridColor   = this.parseSingleColorForGradient(prop.backgroundGridColor);
             prop.colorsStroke           = this.parseSingleColorForGradient(prop.colorsStroke);
             prop.xaxisColor              = this.parseSingleColorForGradient(prop.xaxisColor);
             prop.yaxisColor              = this.parseSingleColorForGradient(prop.yaxisColor);
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
        // @param string color The color to parse for gradients
        //
        this.parseSingleColorForGradient = function (color)
        {
            if (!color || typeof color != 'string') {
                return color;
            }

            if (typeof color === 'string' && color.match(/^gradient\((.*)\)$/i)) {

                // Allow for JSON gradients
                if (color.match(/^gradient\(({.*})\)$/i)) {
                    return RGraph.parseJSONGradient({object: this, def: RegExp.$1});
                }

                var parts = RegExp.$1.split(':');
    
                // Create the gradient

                var grad = this.context.createLinearGradient(0,this.canvas.height - prop.marginBottom, 0, prop.marginTop);
    
                var diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RGraph.trim(parts[0]));
    
                for (var j=1,len=parts.length; j<len; ++j) {
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
        // Waterfall Grow
        // 
        // @param object Options. You can pass frames here - which should be a number
        // @param function An optional function which is called when the animation is finished
        //
        this.grow = function ()
        {
            var opt      = arguments[0] || {};
            var callback = arguments[1] || function () {};
            var frames   = opt.frames || 30;
            var numFrame = 0;
            var obj      = this;
            var data     = RGraph.arrayClone(obj.data);
            
            //Reset The data to zeros
            for (var i=0,len=obj.data.length; i<len; ++i) {
                obj.data[i] /= frames;
            }
            
            //
            // Fix the scale
            //
            if (obj.get('yaxisScaleMax') == null) {
                var max   = obj.getMax(data);
                var scale2 = RGraph.getScale({object: obj, options: {'scale.max': max}});
                obj.set('yaxisScaleMax', scale2.max);
            }
    
            function iterator ()
            {
                for (var i=0; i<obj.data.length; ++i) {
                    
                    // This produces a very slight easing effect
                    obj.data[i] = data[i] * RGraph.Effects.getEasingMultiplier(frames, numFrame);
                }
                
                RGraph.clear(obj.canvas);
                RGraph.redrawCanvas(obj.canvas);
    
                if (++numFrame <= frames) {
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
            var value = this.data[opt.index];

            if (opt.index === this.data.length && prop.total) {
                value = this.total;
            }


            return {
                  index: opt.index,
                dataset: 0,
        sequentialIndex: opt.index,
                  value: value,
                 values: [value]
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
            // Determine the correct color array to use
            var colors = prop.colors;

            if (prop.tooltipsFormattedKeyColors) {
                colors = prop.tooltipsFormattedKeyColors;
            }

            var color = colors[0];
            
            // Change the color for negative bars
            if (specific.value < 0) {
                color = colors[1]; 
            }

            // Change the color for the last bar
            if (specific.index == this.data.length) {
                color = colors[2];
            }
                
            // Figure out the correct label
            if (typeof prop.tooltipsFormattedKeyLabels === 'object' && typeof prop.tooltipsFormattedKeyLabels[specific.index] === 'string') {
                var label = prop.tooltipsFormattedKeyLabels[specific.index];
            } else if (prop.xaxisLabels && typeof prop.xaxisLabels === 'object' && typeof prop.xaxisLabels[specific.index] === 'string') {
                var label = prop.xaxisLabels[specific.index];
            }

            return {
                label: label,
                color: color
            };
        };








        //
        // Now, because canvases can support multiple charts, canvases must always be registered
        //
        RGraph.register(this);








        //
        // This is the 'end' of the constructor so if the first argument
        // contains configuration data - handle that.
        //
        RGraph.parseObjectStyleConfig(this,conf.options);

        return this;
    };