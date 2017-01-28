/*
 data = {

 instrument: {
 strings:[
 ["E2"],["A2"],["D3"],["G3"],["B3"],["E4"]
 ],
 fret:24
 },
 fingerings:[
 {
 difficulty:3,
 fingers:[
 {s: 5, c: 7, lh: 0, rh: 1 ,i:"7" ,t:"B4",color:"#CC4466"},
 {s: 4, c: 8, lh: 1, rh: 1 ,i:"5"  ,t:"G4",color:"#543212"},
 {s: 3, c: 9, lh: 5, rh: 1, i:"3" ,t:"E4" },
 {s: 2, c: 10, lh: 5, rh: 1, i:"1" ,t:"C4" },
 ]
 }
 ],
 options:{
 format:"auto", // portrait,landscape,auto
 autoSize:true, // Set fret num automaticaly
 showInterval:true,
 showTone:true
 }
 }
 */

var FTB = {};

/**
 * Initialize the fretboard
 *
 */
FTB.init = function(datas,jsonList){
    FTB = this;
    FTB.datas = datas;


    FTB.datas.instrument.strings = $.parseJSON(datas.instrument.strings.replace(/&quot;/g, '\"'));


    if(jsonList){
        var jsonDatas = $.parseJSON(jsonList.replace(/&quot;/g, '\"'));
        var aXList = jsonDatas.xList.split(",").map(function(item) {return parseInt(item);});
        var aYList = jsonDatas.yList.split(",").map(function(item) {return parseInt(item);});
        var aIntervaleList = jsonDatas.intervaleList.split(",");
        var aIntervaleColorList = jsonDatas.intervaleColorList.split(",");
        var aDigitAList =jsonDatas.digitAList.split(",").map(function(item) {return parseInt(item);});
        var aWsNameList = jsonDatas.wsNameList.split(",");
        var newFingering = [];
        newFingering[0] =
        {
            difficulty:0,

        }
        newFingering[0].fingers = [];
        $.each(aXList,function(k,v){

            var finger = {
                s:  aYList[k],
                c:  aXList[k],
                i:  aIntervaleList[k],
                t:  aWsNameList[k] + FTB.getOctaveFromDigit(aDigitAList[k]) ,
                color:"#" + aIntervaleColorList[k]
            };
            newFingering[0].fingers.push(finger);
        });

        FTB.datas.fingerings = newFingering;
    }


    /*
     * Need to know if some fingering have openstring
     */
    FTB.haveOpenstring = false;
    FTB.min = 99;
    FTB.max = 0;
    $.each(FTB.datas.fingerings,function(k1,v1){
        $.each(v1.fingers,function(k2,v2){
            if(v2.c == 0){
                FTB.haveOpenstring = true;
            }else{
                if(v2.c<FTB.min){FTB.min = v2.c;}
                if(v2.c>FTB.max){FTB.max = v2.c;}
            }
        });
    });


    FTB.datas.options.currentFrets = 1 + FTB.max - FTB.min;
    if(FTB.datas.options.currentFrets<3){
        FTB.datas.options.currentFrets = 3;
    }

    (FTB.min<3 && FTB.max - FTB.min < 2)?
        FTB.datas.options.currentFret0 = 1:
        FTB.datas.options.currentFret0 = FTB.min;

    FTB.datas.options.roman = ['0','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVII','XIX','XX','XXI','XXII','XXIII','XXIV'];


    return FTB;
};
FTB.getOctaveFromDigit = function(d){
    return parseInt(d / 12);
};
/*
 * Set the format of the fretboard
 */
FTB.setFormat = function(){
    if(FTB.datas.options.format !== "landscape" && FTB.datas.options.format !== "portrait"){
        if(FTB.width > FTB.height){
            FTB.datas.options.format = "landscape";
        }else{
            FTB.datas.options.format = "portrait";
        }
    }
};

FTB.getMousePos = function(canvas,evt) {
    var rect = canvas.getBoundingClientRect();
    return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
    };
};
FTB.playTone = function(tone){
    if(!FTB.synth)
        FTB.synth = new Tone.Synth().toMaster();
    if(!FTB.polySynth)
        FTB.polySynth = new Tone.PolySynth(4, Tone.Synth).toMaster();

    FTB.synth.triggerAttackRelease(tone, "4n");
}
FTB.playTones = function(tones,arpeggio){

    if(arpeggio){

        var myJNSYNTH = JNSYNTH.setSynth("triangle");
        //var piano = new Tone.Synth().toMaster();
        var piano = myJNSYNTH.synth;

        var pianoPart = new Tone.Sequence(function(time, note){
            var n = DIGIT.toneForTone(note);
            piano.triggerAttackRelease(n, "16n", time);
        }, tones,"16n").start();

        pianoPart.loop = false;
        pianoPart.loopEnd = "1m";
        pianoPart.humanize = false;

        Tone.Transport.bpm.value = 100;

        Tone.Transport.start("+0.1");

    }else{
        var myJNSYNTH = JNSYNTH.setSynth("triangle","poly");


        var d = [];

        $.each(tones,function(k,v){
            tones[k] = DIGIT.toneForTone(v);
            d.push("4n");
        });

        myJNSYNTH.synth.triggerAttackRelease(tones, d);
    }



}
FTB.manageMouse = function(canvas,datas,format){


    FTB.canvas.addEventListener('mousedown', function(evt) {
        var mousePos = FTB.getMousePos(canvas,evt);
        var playAll = true;
        $.each(datas.fingerings,function(k1,v1){
            $.each(v1.fingers,function(k2,v2){
                if(
                    mousePos.x >= v2.x0 && mousePos.x <= v2.x1 &&
                    mousePos.y >= v2.y0 && mousePos.y <= v2.y1
                )  {
                    //playAll = false;
                    //FTB.playTone(v2.t);
                }
            });
        });
        if(playAll == true){

        }
    }, false);

    /*
     * Detect when click over string
     */
    FTB.canvas.addEventListener('click', function(evt) {
        var mousePos = FTB.getMousePos(canvas,evt);

        var d = [];
        $.each(datas.fingerings,function(k1,v1){
            $.each(v1.fingers,function(k2,v2){
                d.push(v2.t);
            });
        });

        if(
            mousePos.x >=FTB.mW &&
            mousePos.x <=FTB.width-FTB.mW &&
            mousePos.y >=FTB.mH &&
            mousePos.y <=FTB.height-FTB.mH &&

            mousePos.x >=FTB.width/2
        )  {
            FTB.playTones(d,"arpeggio");
        }else{
            FTB.playTones(d);
        }
    }, false);

};

/*
 * Draw fretboard in canvas
 */
FTB.draw = function(canvas){

    FTB.canvasId = canvas;
    FTB.canvas = document.getElementById(canvas);
    FTB.width = FTB.canvas.width;
    FTB.height = FTB.canvas.height;
    FTB.ctx = FTB.canvas.getContext("2d");
    // Clear draw
    FTB.ctx.clearRect(0, 0, FTB.width, FTB.height);

    // set margins
    FTB.mW = FTB.mH = 20 ;
    // space set for openstring
    FTB.openstringSpace = 22 ;

    FTB.currentFrets = FTB.datas.options.currentFrets;
    FTB.currentFret0 = FTB.datas.options.currentFret0;

    FTB.setFormat();


    FTB.fretboardBackgroundStyle = 'rgba(150, 111, 51,.9)';
    FTB.fretColor = 'silver';
    FTB.fretThickness = 1;
    FTB.stringColor = 'gold';
    FTB.stringThickness = 1;
    FTB.fretboardBorderColor = 'black';
    FTB.fretboardBorderThickness = 1;
    FTB.fretNumFillStyle = 'black';
    FTB.fretNumFontSize = 12;
    FTB.fretNumFont = 'Arial';
    FTB.openstringSpaceColor = 'black';
    FTB.openstringFillStyle = 'black';
    FTB.openstringFontSize = 10;
    FTB.openstringNumFont = 'Arial';
    FTB.tFillStyle = 'black';
    FTB.tFontSize = 13;
    FTB.tFont = 'Courier New';
    FTB.iFillStyle = 'black';
    FTB.iFontSize = 13;
    FTB.iFont = 'Courier New';

    /*
     * Borders and Backgrounds
     */
    //FTB.ctx.fillStyle="#CCCCCC";
    //FTB.ctx.rect(0,0,FTB.width, FTB.height );
    //FTB.ctx.stroke();
    FTB.ctx.fillStyle=FTB.fretboardBackgroundStyle;
    FTB.ctx.fillRect(FTB.mW,FTB.mH,FTB.width-2*FTB.mW, FTB.height -2*FTB.mH);

    /*
     * Fret Loop
     */
    if(FTB.haveOpenstring == true){
        FTB.ctx.fillStyle=FTB.openstringSpaceColor;
        if(FTB.datas.options.format == "landscape") {
            FTB.ctx.fillRect(FTB.mW,FTB.mH,FTB.openstringSpace, FTB.height - 2*FTB.mH);
        }else if(FTB.datas.options.format == "portrait"){
            FTB.ctx.fillRect(FTB.mW,FTB.mH, FTB.width - 2*FTB.mW,FTB.openstringSpace);
        }
    }
    for( var f=0 ; f <= FTB.currentFrets ; f++ ){

        FTB.ctx.beginPath();
        FTB.ctx.strokeStyle = FTB.fretColor ;
        FTB.ctx.lineWidth= FTB.fretThickness ;

        if(FTB.datas.options.format == "landscape"){
            FTB.ctx.moveTo(  FTB.mW + FTB.openstringSpace + f * (( FTB.width - FTB.openstringSpace - 2 *  FTB.mW) / FTB.datas.options.currentFrets  ) -FTB.fretThickness , FTB.mH  );
            FTB.ctx.lineTo(  FTB.mW + FTB.openstringSpace + f * (( FTB.width - FTB.openstringSpace - 2 *  FTB.mW) / FTB.datas.options.currentFrets  )  - FTB.fretThickness, FTB.height - FTB.mH  )  ;
        }else if(FTB.datas.options.format == "portrait"){
            FTB.ctx.moveTo( FTB.mW  , FTB.mH + FTB.openstringSpace + f * (( FTB.height - FTB.openstringSpace - 2 *  FTB.mH) / FTB.datas.options.currentFrets  ) -FTB.fretThickness );
            FTB.ctx.lineTo( FTB.width - FTB.mW , FTB.mH + FTB.openstringSpace + f * (( FTB.height - FTB.openstringSpace - 2 *  FTB.mH) / FTB.datas.options.currentFrets  ) -FTB.fretThickness );
        }
        FTB.ctx.stroke();
    }

    /*
     * Draw freatboard border
     */
    FTB.ctx.beginPath();
    FTB.ctx.strokeStyle = FTB.fretboardBorderColor ;
    FTB.ctx.lineWidth= FTB.fretboardBorderThickness ;
    if(FTB.datas.options.format == "landscape"){
        FTB.ctx.moveTo( FTB.mW  , FTB.mH  );
        FTB.ctx.lineTo( FTB.width -  FTB.mW  , FTB.mH  )  ;
        FTB.ctx.moveTo( FTB.mW  , FTB.height - FTB.mH  );
        FTB.ctx.lineTo( FTB.width -  FTB.mW  , FTB.height - FTB.mH   )  ;
    }else if(FTB.datas.options.format == "portrait"){
        FTB.ctx.moveTo( FTB.mW  , FTB.mH  );
        FTB.ctx.lineTo( FTB.mW  , FTB.height - FTB.mH  )  ;
        FTB.ctx.moveTo( FTB.width -  FTB.mW , FTB.mH  );
        FTB.ctx.lineTo( FTB.width -  FTB.mW , FTB.height - FTB.mH  )  ;
    }
    FTB.ctx.stroke();

    /*
     * String Loop
     */
    for( var s=0 ; s < FTB.datas.instrument.strings.length ; s++ ){
        FTB.ctx.beginPath();
        FTB.ctx.strokeStyle = FTB.stringColor ;
        FTB.ctx.lineWidth= FTB.stringThickness ;

        if(FTB.datas.options.format == "landscape"){
            FTB.ctx.moveTo( FTB.mW , FTB.mH  + s * (( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  ) +(( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  )/2 );
            FTB.ctx.lineTo( FTB.width - FTB.mW , FTB.mH  + s * (( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  ) +(( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  )/2 );
        }else if(FTB.datas.options.format == "portrait"){
            FTB.ctx.moveTo(  FTB.mW  + s * (( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  ) +(( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  )/2   , FTB.mH  );
            FTB.ctx.lineTo(  FTB.mW  + s * (( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  ) +(( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  )/2, FTB.height - FTB.mH  )  ;
        }

        FTB.ctx.stroke();
    }

    /*
     * OpenString names
     */
    $.each(FTB.datas.instrument.strings,function(k,v){
        (FTB.datas.options.format == "landscape") ?
            FTB.ctx.fillText(v, 4 , FTB.height-FTB.mH  - (k+1) * (( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  ) +(( FTB.height -  2 *  FTB.mH) / FTB.datas.instrument.strings.length  )/2 + FTB.openstringFontSize/2 - 2 ):
            FTB.ctx.fillText(v,  FTB.mW - FTB.openstringFontSize/2  + k * (( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  ) +(( FTB.width -  2 *  FTB.mW) / FTB.datas.instrument.strings.length  )/2   , FTB.openstringFontSize );
    });
    /*
     * first fret num
     */
    FTB.ctx.fillStyle = FTB.fretNumFillStyle;
    FTB.ctx.font= FTB.fretNumFontSize + "px " + FTB.fretNumFont;
    (FTB.datas.options.format == "landscape") ?
        FTB.ctx.fillText(FTB.datas.options.roman[FTB.datas.options.currentFret0],  FTB.mW + FTB.openstringSpace , FTB.height - 2  ):
        FTB.ctx.fillText(FTB.datas.options.roman[FTB.datas.options.currentFret0],  2 , FTB.fretNumFontSize + FTB.mH + FTB.openstringSpace );


    /*
     * fingering
     */
    $.each(FTB.datas.fingerings,function(k1,v1){
        $.each(v1.fingers,function(k2,v2){
            if(FTB.datas.options.format == "landscape"){
                if(v2.c > 0){
                    var centerX = FTB.mW + FTB.openstringSpace + ((FTB.width - FTB.openstringSpace - 2 * FTB.mW)/(2*FTB.datas.options.currentFrets)) + (v2.c-FTB.datas.options.currentFret0)*((FTB.width - FTB.openstringSpace - 2 * FTB.mW)/(FTB.datas.options.currentFrets)) ;
                }else if(v2.c == 0){
                    var centerX = FTB.mW + FTB.openstringSpace / 2 ;
                }
                var centerY = FTB.height-FTB.mH - (v2.s  * (( FTB.height  - 2 *  FTB.mH) / FTB.datas.instrument.strings.length  ) +  (( FTB.height  - 2 *  FTB.mH) / FTB.datas.instrument.strings.length  )/2 );
            }else if(FTB.datas.options.format == "portrait"){
                if(v2.c > 0){
                    var centerY = FTB.mH + FTB.openstringSpace + ((FTB.height - FTB.openstringSpace - 2 * FTB.mH)/(2*FTB.datas.options.currentFrets)) + (v2.c-FTB.datas.options.currentFret0)*((FTB.height - FTB.openstringSpace - 2 * FTB.mH)/(FTB.datas.options.currentFrets)) ;
                }else if(v2.c == 0){
                    var centerY = FTB.mH + FTB.openstringSpace / 2 ;
                }
                var centerX  = FTB.mW + (v2.s  * (( FTB.width  - 2 *  FTB.mW) / FTB.datas.instrument.strings.length  ) +  (( FTB.width  - 2 *  FTB.mW) / FTB.datas.instrument.strings.length  )/2 );
            }
            var radius = FTB.openstringSpace/3;

            FTB.ctx.beginPath();
            if(v2.c>0){
                FTB.ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
                FTB.ctx.fillStyle = (v2.color)?v2.color:"#cccccc";
                FTB.ctx.fill();
                FTB.ctx.lineWidth = 1;
                FTB.ctx.strokeStyle = (v2.color)?v2.color:"#cccccc";
                FTB.ctx.stroke();
            }else{
                FTB.ctx.arc(centerX, centerY, radius - 2, 0, 2 * Math.PI, false);
                FTB.ctx.fillStyle = "#000";
                FTB.ctx.fill();
                FTB.ctx.lineWidth = 4;
                FTB.ctx.strokeStyle = v2.color;
                FTB.ctx.stroke();
            }
            v2.x0 = centerX - radius;
            v2.x1 = centerX + radius;
            v2.y0 = centerY - radius;
            v2.y1 = centerY + radius;
            v2.sx0 = centerX-1 ;
            v2.sx1 = centerX+1 ;
            v2.sy0 = centerY-1 ;
            v2.sy1 = centerY+1 ;
            /*
             * Show intervalName
             */
            if(FTB.datas.options.showInterval == true){
                FTB.ctx.beginPath();
                if(v2.i){
                    FTB.ctx.font= "bold " + FTB.iFontSize + "px " + FTB.iFont;
                    FTB.ctx.fillStyle = (v2.color)?v2.color:"#cccccc";
                    (FTB.datas.options.format == "landscape") ?
                        FTB.ctx.fillText(v2.i,  centerX + (v2.i.length*FTB.tFontSize)  , centerY + 1 + radius + FTB.iFontSize  ):
                        FTB.ctx.fillText(v2.i,  centerX - (v2.i.length*FTB.tFontSize/2)  , centerY + 1 + radius + FTB.iFontSize  );
                }
            }
            /*
             * Show toneName
             */
            if(FTB.datas.options.showTone == true){
                FTB.ctx.beginPath();
                if(v2.t){
                    FTB.ctx.font= "bold " + FTB.tFontSize + "px " + FTB.tFont;
                    FTB.ctx.fillStyle = (v2.color)?v2.color:"#cccccc";
                    (FTB.datas.options.format == "landscape") ?
                        FTB.ctx.fillText(v2.t,  centerX - (v2.t.length*FTB.tFontSize)  , centerY - 4 - radius   ):
                        FTB.ctx.fillText(v2.t,  centerX - (v2.t.length*FTB.tFontSize/2)  , centerY - 4 - radius   );
                }
            }
        });
    });


    FTB.manageMouse(FTB.canvas,FTB.datas);
};