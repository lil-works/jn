var fingering = {

    init:function(canvasId,datas,instrumentDatas){


        Fingering = this;

        this.datas = $.parseJSON(datas.replace(/&quot;/g, '\"'));
        this.instrumentDatas = $.parseJSON(instrumentDatas.replace(/&quot;/g, '\"'));


        this.canvasId = canvasId;
        this.c = document.getElementById(canvasId);
        this.width = this.c.width;
        this.height = this.c.height;
        this.ctx = this.c.getContext("2d");
        this.formatedMatrice = [];
        this.minX = 0;

        $.each($.parseJSON(instrumentDatas.replace(/&quot;/g, '\"')), function (index, value) {
            if(!Fingering.formatedMatrice[value.currentString]){
                Fingering.formatedMatrice[value.currentString] = [];
            }
            if(!Fingering.formatedMatrice[value.currentString][value.currentCase]){
                Fingering.formatedMatrice[value.currentString][value.currentCase] = [];
            }
            Fingering.formatedMatrice[value.currentString][value.currentCase]["case"] = value.currentCase;
            Fingering.formatedMatrice[value.currentString][value.currentCase]["string"] = value.currentString;
            Fingering.formatedMatrice[value.currentString][value.currentCase]["digitA"] = value.currentDigitA;
            Fingering.formatedMatrice[value.currentString][value.currentCase]["digit"] = value.currentDigit;
            Fingering.formatedMatrice[value.currentString][value.currentCase]["intervale"] = {};
            Fingering.formatedMatrice[value.currentString][value.currentCase]["octave"] = value.currentOctave;
        });



        Fingering.aXList = this.datas.xList.split(",");
        Fingering.aXListOs = this.datas.xList.split(",");
        Fingering.aYList = this.datas.yList.split(",");
        var os = $.inArray("0", Fingering.aXList )

        if( os == 0  ){
            Fingering.isAnOpenString = "yes";
            Fingering.aXListOs.splice(os,1);
        }else{
            Fingering.isAnOpenString = "no";
        }

        console.log(os,Fingering.canvasId,Fingering.aXList,Fingering.isAnOpenString );
        var aIntervaleList = this.datas.intervaleList.split(",");
        var aDigitAList = this.datas.digitAList.split(",");
        var aWsNameList = this.datas.wsNameList.split(",");



        var recipientsArray = Fingering.aYList.sort();
        var reportRecipientsDuplicate = [];
        for (var i = 0; i < recipientsArray.length - 1; i++) {
            if (recipientsArray[i + 1] == recipientsArray[i]) {
                reportRecipientsDuplicate.push(recipientsArray[i]);
            }
        }
        if(reportRecipientsDuplicate.length>0){
            this.mode = "scale";
        }

        $.each(Fingering.aYList, function (index, value) {
            Fingering.formatedMatrice[value][Fingering.aXList[index]]["intervale"]={intervale:aIntervaleList[index],wsname:aWsNameList[index]}
        });


        if(this.isAnOpenString){
            this.maxX=Math.max.apply(Math,Fingering.aXListOs);
            this.minX=Math.min.apply(Math,Fingering.aXListOs);

        }else{
            this.maxX=Math.max.apply(Math,Fingering.aXList);
            this.minX=Math.min.apply(Math,Fingering.aXList);
        }

        if(this.maxX-this.minX+1 > this.deltaMin){
            this.deltaX = this.maxX-this.minX+1;
        }else{
            this.deltaX = this.deltaMin;
        }
        console.log(this.deltaX);



        this.nbrString = Fingering.formatedMatrice.length;


        this.draw();


        $("#"+canvasId).click(function () {
            jnSynth.play(aDigitAList, Fingering.mode);
        });

        return this;

    },
    draw:function(){


        this.ctx.clearRect(0, 0, this.width, this.height);


        for(i=0;i<this.nbrString;i++){


            this.dotSize = 4;

            this.osH =  20;
            this.nMX = 5;
            this.nMY = 5;
            this.nW  =this.width - 2*this.nMX;
            this.nH = (this.height - 2*this.nMY)-this.osH;
            this.nX0 =  this.nMX;
            this.nY0 = this.osH+this.nMY;
            this.iX =  this.nW/this.nbrString;
            this.iY =  this.nH/this.deltaX;


            this.ctx.beginPath();


            this.ctx.strokeStyle='gold';

            this.ctx.lineWidth=1;

            this.ctx.moveTo( this.nX0 + i * this.iX + this.iX/2, this.nY0);
            this.ctx.lineTo( this.nX0 + i * this.iX + this.iX/2, this.nY0+this.nH )  ;
            this.ctx.stroke();

            // caseloop
            for(j=0;j<this.deltaX+1;j++){

                // if begin of neck
                if(this.minX == 0 || this.minX == 1){
                    this.ctx.beginPath();
                    this.ctx.strokeStyle='#CCC';
                    this.ctx.lineWidth=0.1;
                    this.ctx.moveTo(this.nX0 ,this.nY0 - 3  );
                    this.ctx.lineTo(this.nX0 + this.nW  ,this.nY0 - 3)  ;
                    this.ctx.stroke();
                }

                console.log(this.canvasId,this.minX);

                if(this.minX > 0){
                    this.ctx.beginPath();
                    this.ctx.fillStyle = "black";
                    this.ctx.font="9px Arial";
                    this.ctx.fillText(this.roman[this.minX],   0 , this.nY0+9);

                    this.ctx.beginPath();
                    this.ctx.strokeStyle='#444';
                    this.ctx.lineWidth=.1;
                    this.ctx.moveTo(this.nX0 ,this.nY0 + j * this.iY  );
                    this.ctx.lineTo(this.nX0 + this.nW ,this.nY0 + j * this.iY)  ;
                    this.ctx.stroke();



                    if(this.formatedMatrice[i][j+this.minX]["intervale"].wsname){

                        Fingering.ctx.fillStyle = "black";
                        Fingering.ctx.fillRect(
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - Fingering.dotSize/2,
                            Fingering.nY0 + j * Fingering.iY + Fingering.iY/2 - Fingering.dotSize/2
                            ,Fingering.dotSize,Fingering.dotSize);


                        Fingering.ctx.font="10px Georgia";
                        Fingering.ctx.fillText(
                            Translator.trans(this.formatedMatrice[i][j+this.minX]["intervale"].wsname),
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - 4,
                            Fingering.nY0 + j * Fingering.iY + Fingering.iY/2 - 4
                            );
                        Fingering.ctx.fillText(
                            this.formatedMatrice[i][j+this.minX]["intervale"].intervale,
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - 4,
                            Fingering.nY0 + j * Fingering.iY + Fingering.iY/2 + 11
                        );
                    }
                }else{
                    this.ctx.beginPath();
                    this.ctx.fillStyle = "black";
                    this.ctx.font="9px Arial";
                    this.ctx.fillText(this.roman[1],   0 , this.nY0+9);

                    this.ctx.beginPath();
                    this.ctx.strokeStyle='#444';
                    this.ctx.lineWidth=.1;
                    this.ctx.moveTo(this.nX0 ,this.nY0 + j * this.iY  );
                    this.ctx.lineTo(this.nX0 + this.nW ,this.nY0 + j * this.iY)  ;
                    this.ctx.stroke();


                    if(this.formatedMatrice[i][j+this.minX]["intervale"].wsname){

                        Fingering.ctx.fillStyle = "black";
                        Fingering.ctx.fillRect(
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - Fingering.dotSize/2,
                            Fingering.nY0 + (j-1) * Fingering.iY + Fingering.iY/2 - Fingering.dotSize/2
                            ,Fingering.dotSize,Fingering.dotSize);
                        Fingering.ctx.font="10px Georgia";
                        Fingering.ctx.fillText(
                            this.formatedMatrice[i][j+this.minX]["intervale"].wsname,
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - 4,
                            Fingering.nY0 + (j-1) * Fingering.iY + Fingering.iY/2 - 4
                        );
                        Fingering.ctx.fillText(
                            this.formatedMatrice[i][j+this.minX]["intervale"].intervale,
                            Fingering.nX0 + i * Fingering.iX + Fingering.iX/2 - 4,
                            Fingering.nY0 + (j-1) * Fingering.iY + Fingering.iY/2 +11
                        );
                    }
                }
            }





        }





    },
    isAnOpenString:null,
    mode:"chord",
    formatedMatrice:null,
    nbrString:0,
    minX:0,
    minY:0,
    deltaMin:3,
    deltaX:5,
    roman:['0','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVII','XIX','XX','XXI','XXII','XXIII','XXIV'],


};