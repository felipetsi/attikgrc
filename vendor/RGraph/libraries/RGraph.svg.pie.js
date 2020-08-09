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

    RGraph     = window.RGraph || {isrgraph:true,isRGraph:true,rgraph:true};
    RGraph.SVG = RGraph.SVG || {};

// Module pattern
(function (win, doc, undefined)
{
    RGraph.SVG.Pie = function (conf)
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








        this.id              = conf.id;
        this.uid             = RGraph.SVG.createUID();
        this.container       = document.getElementById(this.id);
        this.layers          = {}; // MUST be before the SVG tag is created!
        this.svg             = RGraph.SVG.createSVG({object: this,container: this.container});
        this.isRGraph        = true;
        this.isrgraph        = true;
        this.rgraph          = true;
        this.width           = Number(this.svg.getAttribute('width'));
        this.height          = Number(this.svg.getAttribute('height'));
        this.data            = conf.data;
        this.type            = 'pie';
        this.angles          = [];
        this.colorsParsed    = false;
        this.originalColors  = {};
        this.gradientCounter = 1;
        this.nodes           = [];
        this.shadowNodes     = [];












        // Add this object to the ObjectRegistry
        RGraph.SVG.OR.add(this);
        
        // Set the DIV container to be inline-block
        this.container.style.display = 'inline-block';

        this.properties =
        {
            centerx: null,
            centery: null,
            radius:  null,
            
            marginLeft:    35,
            marginRight:   35,
            marginTop:     35,
            marginBottom:  35,
            
            colors: [
                '#f66', '#6f6', '#66f', '#ff6', '#6ff', '#ccc',
                'pink', 'orange', 'cyan', 'maroon', 'olive', 'teal'
            ],
            colorsStroke:      'rgba(0,0,0,0)',
            
            textColor: 'black',
            textFont: 'Arial, Verdana, sans-serif',
            textSize: 12,
            textBold: false,
            textItalic: false,
            labels: [],
            labelsSticks: true,
            labelsSticksOffset: 50,

            linewidth: 1,
            
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

            highlightStroke: 'rgba(0,0,0,0)',
            highlightFill: 'rgba(255,255,255,0.7)',
            highlightLinewidth: 1,
            highlightStyle: 'normal',
            highlightStyleOutlineWidth: 7,
            
            title:       '',
            titleX:      null,
            titleY:      null,
            titleHalign: 'center',
            titleValign: null,
            titleSize:   null,
            titleColor:  null,
            titleFont:   null,
            titleBold:   null,
            titleItalic: null,
            
            titleSubtitle:       null,
            titleSubtitleSize:   null,
            titleSubtitleColor:  '#aaa',
            titleSubtitleFont:   null,
            titleSubtitleBold:   null,
            titleSubtitleItalic: null,
            
            shadow: false,
            shadowOffsetx: 2,
            shadowOffsety: 2,
            shadowBlur: 2,
            shadowOpacity: 0.25,
            
            exploded: 0,
            roundRobinMultiplier: 1,
            
            donut:              false,
            donutWidth:         75,

            key:            null,
            keyColors:      null,
            keyOffsetx:     0,
            keyOffsety:     0,
            keyLabelsOffsetx: 0,
            keyLabelsOffsety: -1,
            keyLabelsColor:   null,
            keyLabelsFont:    null,
            keyLabelsSize:    null,
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













            //(Re)set this so it doesn't grow endlessly
            this.angles = [];











            // Should the first thing that's done inthe.draw() function
            // except for the onbeforedraw event
            this.width  = Number(this.svg.getAttribute('width'));
            this.height = Number(this.svg.getAttribute('height'));















            // Create the defs tag if necessary
            RGraph.SVG.createDefs(this);




            this.graphWidth  = this.width - prop.marginLeft - prop.marginRight;
            this.graphHeight = this.height - prop.marginTop - prop.marginBottom;



            // Work out the center point
            this.centerx = (this.graphWidth / 2) + prop.marginLeft;
            this.centery = (this.graphHeight / 2) + prop.marginTop;
            this.radius  = Math.min(this.graphWidth, this.graphHeight) / 2;



            // Allow the user to override the calculated centerx/y/radius
            this.centerx = typeof prop.centerx === 'number' ? prop.centerx : this.centerx;
            this.centery = typeof prop.centery === 'number' ? prop.centery : this.centery;
            this.radius  = typeof prop.radius  === 'number' ? prop.radius  : this.radius;
            
            //
            // Allow the centerx/centery/radius to be a plus/minus
            //
            if (typeof prop.radius === 'string' && prop.radius.match(/^\+|-\d+$/) )   this.radius  += parseFloat(prop.radius);
            if (typeof prop.centerx === 'string' && prop.centerx.match(/^\+|-\d+$/) ) this.centerx += parseFloat(prop.centerx);
            if (typeof prop.centery === 'string' && prop.centery.match(/^\+|-\d+$/) ) this.centery += parseFloat(prop.centery);


            // Parse the colors for gradients
            // Must be after the cx/cy/r calculations
            RGraph.SVG.resetColorsToOriginalValues({object:this});
            this.parseColors();


            // Go through the data and work out the maximum value
            this.max   = RGraph.SVG.arrayMax(this.data);
            this.total = RGraph.SVG.arraySum(this.data);

            // Set the explosion to be an array if it's a number
            if (typeof prop.exploded === 'number' && prop.exploded > 0) {
                var val = prop.exploded;
    
                prop.exploded = [];
    
                for (var i=0; i<this.data.length; ++i) {
                    prop.exploded[i] = val;
                }
            }

            

            // Draw the segments
            this.drawSegments({shadow: true});



            // Draw the title and subtitle
            RGraph.SVG.drawTitle(this);



            // Draw the labels
            if (prop.labelsSticks) {
                this.drawLabelsSticks();
            } else {
                this.drawLabels();
            }
            
            
            //
            // Draw the ingraph labels if required
            //
            this.drawIngraphLabels();




            // Draw the key
            if (typeof prop.key !== null && RGraph.SVG.drawKey) {
                RGraph.SVG.drawKey(this);
            } else if (!RGraph.SVG.isNull(prop.key)) {
                alert('The drawKey() function does not exist - have you forgotten to include the key library?');
            }

            



            // Add the event listener that clears the highlight if
            // there is any. Must be MOUSEDOWN (ie before the click event)
            var obj = this;
            document.body.addEventListener('mousedown', function (e)
            {
                RGraph.SVG.removeHighlight(obj);
            }, false);



            // Fire the draw event
            RGraph.SVG.fireCustomEvent(this, 'ondraw');



            return this;
        };








        //
        // Draws the segments
        //
        // @param bool     Whether or not this is a redraw. If this is a redraw
        //                 shadows are omitted
        //
        this.drawSegments = function (opt)
        {
            var start   = 0,
                end     = 0,
                angle   = 0,
                sum     = RGraph.SVG.arraySum(this.data),
                segment = 0;




            // Work out the start and end angles for the data
            for (var i=0,len=this.data.length; i<len; ++i) {
            
                var value = this.data[i] * prop.roundRobinMultiplier;

                start   = angle;
                segment = ((value / sum) * RGraph.SVG.TRIG.TWOPI);
                end     = start + segment;

                var explosion = RGraph.SVG.TRIG.getRadiusEndPoint({
                    angle: start + (segment / 2),
                    r: prop.exploded[i]
                });

                var explosionX = explosion[1],
                    explosionY = explosion[0];


                this.angles[i] = {
                    start:   start,
                    end:     end,
                    angle:   end - start,
                    halfway: ((end - start) / 2) + start,
                    cx:      this.centerx + (parseFloat(explosionX) || 0),
                    cy:      this.centery - (parseFloat(explosionY) || 0),
                    radius:  this.radius,
                    object: this
                };

                // Increase the angle at which we start drawing the next segment at
                angle += (end - start);
            }



            if (opt.shadow) {
                RGraph.SVG.setShadow({
                    object:  this,
                    offsetx: prop.shadowOffsetx,
                    offsety: prop.shadowOffsety,
                    blur:    prop.shadowBlur,
                    opacity: prop.shadowOpacity,
                    id:      'dropShadow'
                });
            }


            //
            // This loop goes thru the angles that were
            // generated above and adds them to the
            // scene
            //
            for (var i=0; i<this.angles.length; ++i) {

                var path = RGraph.SVG.TRIG.getArcPath({
                    cx:    this.angles[i].cx,
                    cy:    this.angles[i].cy,
                    r:     this.radius,
                    start: this.angles[i].start,
                    end:   this.angles[i].end
                });





                // Donut
                if (prop.donut) {
                
                    var donutWidth = prop.donutWidth;
                
                    var donut_path = RGraph.SVG.TRIG.getArcPath3({
                        cx:     this.angles[i].cx,
                        cy:     this.angles[i].cy,
                        r:      this.radius - donutWidth,
                        start:  this.angles[i].end,
                        end:    this.angles[i].start,
                        moveto: false,
                        anticlockwise: true
                    });

                    var xy = RGraph.SVG.TRIG.getRadiusEndPoint({
                        angle: this.angles[i].end - RGraph.SVG.TRIG.HALFPI,
                        r:     this.radius - donutWidth
                    });
                    
                
                
                
                    path =   path
                           + " L {1} {2} ".format(xy[0] + this.angles[i].cx, xy[1] + this.angles[i].cy)
                           + donut_path
                           + " Z";
                
                
                } else {
                
                    path = path + " L {1} {2} ".format(
                        this.angles[i].cx,
                        this.angles[i].cy
                    ) + " Z"
                }



                var arc = RGraph.SVG.create({
                    svg: this.svg,
                    parent: this.svg.all,
                    type: 'path',
                    attr: {
                        d: path,
                        fill: prop.colors[i],
                        stroke: prop.colorsStroke,
                        'stroke-width': prop.linewidth,
                        'data-tooltip': (!RGraph.SVG.isNull(prop.tooltips) && prop.tooltips.length) ? prop.tooltips[i] : '',
                        'data-index': i,
                        'data-value': value,
                        'data-start-angle': this.angles[i].start,
                        'data-end-angle': this.angles[i].end,
                        'data-radius': this.radius,
                        filter: (prop.shadow && opt.shadow) ? 'url(#dropShadow)' : ''
                    }
                });

                // Store the path with the relevant entry in the obj.angles array
                this.angles[i].element = arc;
                

                // Store a reference to the node
                if (prop.shadow && opt.shadow) {
                    this.shadowNodes[i] = arc;
                } else {
                    this.nodes[i] = arc;
                }

                if (prop.tooltips && (prop.tooltips[i] || typeof prop.tooltips === 'string') && (!opt.shadow || !prop.shadow)) {
                
                    // Make the tooltipsEvent default to click
                    if (prop.tooltipsEvent !== 'mousemove') {
                        prop.tooltipsEvent = 'click';
                    }

                    (function (index, obj)
                    {
                        arc.addEventListener(prop.tooltipsEvent, function (e)
                        {
                            // If the event for tooltips is mousemove and the
                            // tooltip is already visible then do nothing
                            var tooltip = RGraph.SVG.REG.get('tooltip');
                            if (tooltip && prop.tooltipsEvent === 'mousemove' && index === tooltip.__index__) {
                                return;
                            }





                            obj.removeHighlight();

                            // Show the tooltip
                            RGraph.SVG.tooltip({
                                object:          obj,
                                index:           index,
                                sequentialIndex: index,
                                text:            typeof prop.tooltips === 'string' ? prop.tooltips : prop.tooltips[index],
                                event:           e
                            });
                            
                            // Highlight the rect that has been clicked on
                            obj.highlight(e.target);
                            
                            var highlight = RGraph.SVG.REG.get('highlight');
                            
                            if (prop.tooltipsEvent === 'mousemove') {
                                highlight.style.cursor = 'pointer';
                            }
                            
                        }, false);

                        // Install the event listener that changes the
                        // cursor if necessary
                        if (prop.tooltipsEvent === 'click') {
                            arc.addEventListener('mousemove', function (e)
                            {
                                e.target.style.cursor = 'pointer';
                            }, false);
                        }
                        
                    }(i, this));
                }
            }

            //
            // Redraw the segments if necessary so that they're on
            // top of any shadow
            //
            if (prop.shadow && opt.shadow) {
                this.redrawSegments();
            }
        };








        //
        // Redraw the Bars o that the bars appear above any shadow
        //
        this.redrawSegments = function ()
        {
            this.drawSegments({shadow: false});
        };








        //
        // Draw the labels
        //
        this.drawLabels = function ()
        {
            var angles = this.angles,
                prop   = this.properties,
                labels = prop.labels;

            for (var i=0; i<angles.length; ++i) {
                
                var endpoint = RGraph.SVG.TRIG.getRadiusEndPoint({
                    angle: angles[i].halfway - RGraph.SVG.TRIG.HALFPI,
                    r: angles[i].radius + 15
                });
                
                var x = endpoint[0] + angles[i].cx,
                    y = endpoint[1] + angles[i].cy,
                    valign,
                    halign;

                // Figure out the valign and halign based on the quadrant
                // the center of the sgement is in.
                if (angles[i].halfway > 0 && angles[i].halfway < RGraph.SVG.TRIG.HALFPI) {
                    halign = 'left';
                    valign = 'bottom';
                } else if (angles[i].halfway > RGraph.SVG.TRIG.HALFPI && angles[i].halfway < RGraph.SVG.TRIG.PI) {
                    halign = 'left';
                    valign = 'top';
                } else if (angles[i].halfway > RGraph.SVG.TRIG.PI && angles[i].halfway < (RGraph.SVG.TRIG.HALFPI + RGraph.SVG.TRIG.PI)) {
                    halign = 'right';
                    valign = 'top';
                } else if (angles[i].halfway > (RGraph.SVG.TRIG.HALFPI + RGraph.SVG.TRIG.PI) && angles[i].halfway < RGraph.SVG.TRIG.TWOPI) {
                    halign = 'right';
                    valign = 'top';
                }

                RGraph.SVG.text({
                    object: this,
                    parent: this.svg.all,
                    tag:    'labels',
                    text:   typeof labels[i] === 'string' ? labels[i] : '',
                    x:      x,
                    y:      y,
                    valign: valign,
                    halign: halign,
                    
                    font:   prop.labelsFont || prop.textFont,
                    size:   typeof prop.labelsSize === 'number' ? prop.labelsSize : prop.textSize,
                    bold:   typeof prop.labelsBold === 'boolean' ? prop.labelsBold : prop.textBold,
                    italic: typeof prop.labelsItalic === 'boolean' ? prop.labelsItalic : prop.textItalic,
                    color:  prop.labelsColor || prop.textColor
                });
            }
        };








        //
        // Draws the ingraph labels
        //
        this.drawIngraphLabels = function ()
        {
            if (prop.labelsIngraph) {

                for (var i=0; i<this.angles.length; ++i) {
    
                    // Some defaults
                    var halign   = prop.labelsIngraphHalign || 'center',
                        valign   = prop.labelsIngraphValign || 'center',

                        font   = prop.labelsIngraphFont || prop.textFont,
                        size   = typeof prop.labelsIngraphSize   === 'number'  ? prop.labelsIngraphSize   : prop.textSize,
                        italic = typeof prop.labelsIngraphItalic === 'boolean' ? prop.labelsIngraphItalic : prop.textItalic,
                        bold   = typeof prop.labelsIngraphBold   === 'boolean' ? prop.labelsIngraphBold   : prop.textBold,
                        color  = prop.labelsIngraphColor || prop.textColor,

                        bgcolor  = prop.labelsIngraphBackground  || 'transparent',
                        decimals = prop.labelsIngraphDecimals    || 0,
                        padding  = typeof prop.labelsIngraphBackground === 'string' ? 3 : 0;

                    // Work out the coordinates
                    var xy = RGraph.SVG.TRIG.getRadiusEndPoint({
                        angle: this.angles[i].halfway - RGraph.SVG.TRIG.HALFPI,
                            r: this.angles[i].radius * (typeof prop.labelsIngraphRadiusPos === 'number' ? prop.labelsIngraphRadiusPos : 0.5)
                    });
                    
                    if (typeof prop.labelsIngraphSpecific === 'object' && prop.labelsIngraphSpecific) {
                        if (typeof prop.labelsIngraphSpecific[i] === 'string') {
                            var str = prop.labelsIngraphSpecific[i];
                        } else {
                            var str = '';
                        }
                    } else {
                        if (typeof prop.labelsIngraphFormatter === 'function') {
                            var str = prop.labelsIngraphFormatter({
                                object: this,
                                number: this.data[i].toFixed(decimals)
                            })
                        } else {

                            var str = RGraph.SVG.numberFormat({
                                prepend:  prop.labelsIngraphUnitsPre,
                                append:   prop.labelsIngraphUnitsPost,
                                point:    prop.labelsIngraphPoint,
                                thousand: prop.labelsIngraphThousand,
                                num:      this.data[i].toFixed(decimals),
                                object: this
                            });
                        }
                    }
    
                    // Draw the text
                    RGraph.SVG.text({
                        object:     this,
                        parent:     this.svg.all,
                        tag:        'labels.ingraph',
                        x:          this.angles[i].cx + xy[0],
                        y:          this.angles[i].cy + xy[1],
                        text:       str,
                        halign:     halign,
                        valign:     valign,
                        
                        font:   font,
                        size:   size,
                        bold:   bold,
                        italic: italic,
                        color:  color,

                        background: bgcolor,
                        padding:    padding
                    });
                }
            }
        };








        //
        // This function draws the labels in a list format
        //
        this.drawLabelsSticks = function ()
        {
            var labels_right  = [],
                labels_left   = [],
                labels_coords = [];

            for (var i=0; i<this.angles.length; ++i) {

                var angle          = (this.angles[i].start + ((this.angles[i].end - this.angles[i].start) / 2)) - RGraph.SVG.TRIG.HALFPI, // Midpoint
                    
                    endpoint_inner = RGraph.SVG.TRIG.getRadiusEndPoint({angle: angle, r: this.radius + 5}),
                    endpoint_outer = RGraph.SVG.TRIG.getRadiusEndPoint({angle: angle, r: this.radius + 30}),
                    
                    explosion = [
                        (typeof prop.exploded === 'number' ? prop.exploded : prop.exploded[i]),
                        Math.cos(angle) * (typeof prop.exploded === 'number' ? prop.exploded : prop.exploded[i]),
                        Math.sin(angle) * (typeof prop.exploded === 'number' ? prop.exploded : prop.exploded[i])
                    ];
                
                // Initialise this array
                labels_coords[i] = [];
                
                // Initialise this
                var labels = {};





                // Push the label into the correct array
                if (angle > RGraph.SVG.TRIG.HALFPI) {
                
                    var index = labels_left.length;

                    labels_left[index]        = [];
                    labels_left[index].text   = prop.labels[i];
                    labels_left[index].halign = 'right';
                    labels                    = labels_left;

                    labels_coords[i].halign = 'right';
                } else {
                    
                    var index = labels_right.length; 

                    labels_right[index]        = [];
                    labels_right[index].text   = prop.labels[i];
                    labels_right[index].halign = 'right';
                    labels                     = labels_right;

                    labels_coords[i].halign = 'left';
                }







                endpoint_inner[0] += (explosion[1] || 0);
                endpoint_inner[1] += (explosion[2] || 0);
                
                endpoint_outer[0] += (explosion[1] || 0);
                endpoint_outer[1] += (explosion[2] || 0);
            
                var x,y;

                if (labels[index].text) {
                    var stick = RGraph.SVG.create({
                        svg: this.svg,
                        parent: this.svg.all,
                        type: 'path',
                        attr: {
                            d: 'M {1} {2} L {3} {4}'.format(
                                this.centerx + endpoint_inner[0],
                                this.centery + endpoint_inner[1],
                                this.centerx + endpoint_outer[0],
                                this.centery + endpoint_outer[1]
                            ),
                            stroke: '#999',
                            fill: 'rgba(0,0,0,0)'
                        }
                    });
                }
                
                // The path is altered later so this needs saving
                if (stick) {
                    labels[index].stick = stick;
                }
                
                x = (this.centerx + endpoint_outer[0] + (angle > 1.57 ? -50 : 50));
                y = (this.centery + endpoint_outer[1]);


                labels_coords[i].x      = x ;
                labels_coords[i].y      = y;
                labels_coords[i].text = prop.labels[i];
            }

            // Calculate the spacing for each side
            var vspace_right = (this.height - prop.marginTop - prop.marginBottom) / labels_right.length;
            var vspace_left  = (this.height - prop.marginTop - prop.marginBottom) / labels_left.length;

            // Reset these
            x = y = 0;





            // Loop through the RHS labels
            for (var i=0; i<labels_right.length; ++i) {
                if (labels_right[i] && labels_right[i].text) {

                    x = this.centerx + this.radius + prop.labelsSticksOffset;
                    y = prop.marginTop + (vspace_right * i) + (vspace_right / 2);


                    // Add the label to the scene
                    RGraph.SVG.text({
                        object: this,
                        parent: this.svg.all,
                        tag:    'labels.sticks',
                        text:   typeof labels_right[i].text === 'string' ? labels_right[i].text : '',
                        x:      x,
                        y:      y,
                        valign: 'center',
                        halign: labels_right[i].text,
                        
                        font:   prop.labelsFont || prop.textFont,
                        size:   typeof prop.labelsSize   === 'number'  ? prop.labelsSize   : prop.textSize,
                        bold:   typeof prop.labelsBold   === 'boolean' ? prop.labelsBold   : prop.textBold,
                        italic: typeof prop.labelsItalic === 'boolean' ? prop.labelsItalic : prop.textItalic,
                        color:  prop.labelsColor || prop.textColor
                    });
                    
                    // Now update the path of the stick
                    var str = labels_right[i].stick.getAttribute('d').replace(/ L /, ' Q ') + ' {1} {2}';

                    labels_right[i].stick.setAttribute(
                        'd',
                        str.format(
                            x - 5,
                            y
                        )
                    );
                }
            }





            // Loop through the LHS labels
            for (var i=0; i<labels_left.length; ++i) {
                if (labels_left[i] && labels_left[i].text) {

                    x = this.centerx - this.radius - prop.labelsSticksOffset;
                    y = this.height - (prop.marginTop + (vspace_left * i) + (vspace_left / 2));

                
                    // Add the label to the scene
                    RGraph.SVG.text({
                        object: this,
                        parent: this.svg.all,
                        tag:    'labels.sticks',
                        text:   typeof labels_left[i].text === 'string' ? labels_left[i].text : '',
                        x:      x - 7,
                        y:      y,
                        valign: 'center',
                        halign: labels_left[i].halign,
                        
                        font:   prop.labelsFont || prop.textFont,
                        size:   typeof prop.labelsSize   === 'number'  ? prop.labelsSize   : prop.textSize,
                        bold:   typeof prop.labelsBold   === 'boolean' ? prop.labelsBold   : prop.textBold,
                        italic: typeof prop.labelsItalic === 'boolean' ? prop.labelsItalic : prop.textItalic,
                        color:  prop.labelsColor || prop.textColor
                    });
                    
                    // Now update the path of the stick
                    var str = labels_left[i].stick.getAttribute('d').replace(/ L /, ' Q ') + ' {1} {2}';

                    labels_left[i].stick.setAttribute(
                        'd',
                        str.format(
                            x - 5,
                            y
                        )
                    );
                }
            }
        };








        //
        // This function can be used to highlight a segment on the chart
        // 
        // @param object segment The segment to highlight
        //
        this.highlight = function (segment)
        {
            // Outline style highlighting
            if (prop.highlightStyle === 'outline') {
                
                var index = segment.getAttribute('data-index');
            
                var path = RGraph.SVG.TRIG.getArcPath3({
                    start:          this.angles[index].start,
                    end:            this.angles[index].end,
                    cx:             this.angles[index].cx,
                    cy:             this.angles[index].cy,
                    r:              this.angles[index].radius + 2,
                    anticlockwise:  false,
                    lineto:         false
                });
            
                // Add the reverse arc
                path += RGraph.SVG.TRIG.getArcPath3({
                    start:          this.angles[index].end,
                    end:            this.angles[index].start,
                    cx:             this.angles[index].cx,
                    cy:             this.angles[index].cy,
                    r:              this.angles[index].radius + 2 + prop.highlightStyleOutlineWidth,
                    anticlockwise:  true
                });
            
                var highlight = RGraph.SVG.create({
                    svg: this.svg,
                    parent: this.svg.all,
                    type: 'path',
                    attr: {
                        d: path,
                        fill: prop.colors[index],
                        stroke: 'transparent'
                    },
                    style: {
                        pointerEvents: 'none'
                    }
                });
            
            // Regular highlighting
            } else {
            
                var highlight = RGraph.SVG.create({
                    svg: this.svg,
                    parent: this.svg.all,
                    type: 'path',
                    attr: {
                        d: segment.getAttribute('d'),
                        fill: prop.highlightFill,
                        stroke: prop.highlightStroke,
                        'stroke-width': prop.highlightLinewidth
                    },
                    style: {
                        pointerEvents: 'none'
                    }
                });
            }

            if (prop.tooltipsEvent === 'mousemove') {
                highlight.addEventListener('mouseout', function (e)
                {
                    highlight.parentNode.removeChild(highlight);
                    RGraph.SVG.hideTooltip();

                    RGraph.SVG.REG.set('highlight', null);
                }, false);
            }


            // Store the highlight rect in the registry so
            // it can be cleared later
            RGraph.SVG.REG.set('highlight', highlight);
        };








        //
        // This allows for easy specification of gradients
        //
        this.parseColors = function () 
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (!Object.keys(this.originalColors).length) {
                this.originalColors = {
                    colors:        RGraph.SVG.arrayClone(prop.colors),
                    highlightFill: RGraph.SVG.arrayClone(prop.highlightFill)
                }
            }
            
            
            // colors
            var colors = prop.colors;

            if (colors) {
                for (var i=0; i<colors.length; ++i) {
                    colors[i] = RGraph.SVG.parseColorRadial({
                        object: this,
                        color: colors[i]
                    });
                }
            }
            
            // Highlight fill
            prop.highlightFill = RGraph.SVG.parseColorRadial({
                object: this,
                color: prop.highlightFill
            });
        };








        //
        // A roundRobin effect for the Pie chart
        //
        // @param object    Options for the effect
        // @param function  An optional callback function to call when
        //                  the effect is complete
        //
        this.roundRobin = function ()
        {
            var obj          = this,
                opt          = arguments[0] || {},
                data         = RGraph.SVG.arrayClone(this.data),
                prop         = this.properties,
                frame        = 1,
                frames       = opt.frames || 30,
                callback     = typeof opt.callback === 'function' ? opt.callback : function () {},
                dataSum      = RGraph.SVG.arraySum(this.data),
                textColor    = prop.textColor,
                ingraph      = prop.labelsIngraph,
                multiplier   = 0;
            
            // Set the text colors to transparent
            prop.textColor     = 'rgba(0,0,0,0)';
            prop.labelsIngraph = false;


            // Draw the chart first
            obj.draw();
            
            // Now get the resulting angles
            var angles = RGraph.SVG.arrayClone(this.angles);


            function iterator ()
            {
                multiplier =  (1 / frames) * frame++;

                for (var i=0; i<angles.length; ++i) {

                    var value = obj.data[i];

                    obj.angles[i].start = angles[i].start * multiplier;
                    obj.angles[i].end   = angles[i].end   * multiplier;

                    //var segment = (((value * prop.roundRobinMultiplier) / dataSum) * RGraph.SVG.TRIG.TWOPI);
                    var segment = ((obj.angles[i].end - obj.angles[i].start) / 2),
                        explodedX = Math.cos(obj.angles[i].start + segment - RGraph.SVG.TRIG.HALFPI) * (prop.exploded[i] || 0),
                        explodedY = Math.sin(obj.angles[i].start + segment - RGraph.SVG.TRIG.HALFPI) * (prop.exploded[i] || 0);



                    var path = RGraph.SVG.TRIG.getArcPath({
                        cx:    obj.centerx + explodedX,
                        cy:    obj.centery + explodedY,
                        r:     obj.radius,
                        start: obj.angles[i].start,
                        end:   obj.angles[i].end
                    });





                    // Donut
                    if (prop.donut) {
                    
                        var donutWidth = prop.donutWidth;
                    
                        var donut_path = RGraph.SVG.TRIG.getArcPath3({ 
                            cx:     obj.angles[i].cx,
                            cy:     obj.angles[i].cy,
                            r:      obj.radius - donutWidth,
                            start:  obj.angles[i].end,
                            end:    obj.angles[i].start,
                            moveto: false,
                            anticlockwise: true
                        });
                        
                        var xy = RGraph.SVG.TRIG.getRadiusEndPoint({
                            angle: obj.angles[i].end - RGraph.SVG.TRIG.HALFPI,
                            r:     obj.radius - donutWidth
                        });
                    
                        path =   path
                               + " L {1} {2} ".format(xy[0] + obj.angles[i].cx, xy[1] + obj.angles[i].cy)
                               + donut_path
                               + " Z";
                    
                    } else {
                    
                        path = path + " L {1} {2} ".format(
                            obj.angles[i].cx,
                            obj.angles[i].cy
                        ) + " Z"
                    }









                    path = path + " L {1} {2} Z".format(
                        obj.centerx + explodedX,
                        obj.centery + explodedY
                    );

                    if (obj.shadowNodes && obj.shadowNodes[i]) {
                        obj.shadowNodes[i].setAttribute('d', path);
                    }
                    obj.nodes[i].setAttribute('d', path);
                }


                if (frame <= frames) {
                    RGraph.SVG.FX.update(iterator);
                } else {
                    prop.textColor     = textColor;
                    prop.labelsIngraph = ingraph;

                    RGraph.SVG.redraw(obj.svg);

                    callback(obj);
                }
            }
            
            iterator();

            return this;
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
        this.tooltipsFormattedCustom = function (specific, index, colors)
        {
            var color = colors[specific.index];
            var label = ( (typeof prop.tooltipsFormattedKeyLabels === 'object' && typeof prop.tooltipsFormattedKeyLabels[specific.index] === 'string') ? prop.tooltipsFormattedKeyLabels[specific.index] : '');

            return {
                label: label,
                color: color
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