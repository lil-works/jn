var fingering2 = {


    init:function(canvasId,datas,instrumentDatas){


        F = this;

        this.datas = $.parseJSON(datas.replace(/&quot;/g, '\"'));
        this.instrumentDatas = $.parseJSON(instrumentDatas.replace(/&quot;/g, '\"'));


        this.canvasId = canvasId;
        this.c = document.getElementById(canvasId);
        this.width = this.c.width;
        this.height = this.c.height;
        this.ctx = this.c.getContext("2d");


        this.aXList = this.datas.xList.split(",").map(function(item) {return parseInt(item);});
        this.aYList = this.datas.yList.split(",").map(function(item) {return parseInt(item);});
        this.aIntervaleList = this.datas.intervaleList.split(",");
        var aIntervaleColorList = this.datas.intervaleColorList.split(",");
        var aDigitAList = this.datas.digitAList.split(",").map(function(item) {return parseInt(item);});
        this.aWsNameList = this.datas.wsNameList.split(",");


        console.log(F.aDigitAList);
        /*
         * Set formatedMatrice
         */
        this.FM = [];

        $.each(this.instrumentDatas, function (index, value) {
            if(!F.FM[value.currentString]){
                F.FM[value.currentString] = [];
            }
            if(!F.FM[value.currentString][value.currentCase]){
                F.FM[value.currentString][value.currentCase] = [];
            }
            F.FM[value.currentString][value.currentCase]["case"] = value.currentCase;
            F.FM[value.currentString][value.currentCase]["string"] = value.currentString;
            F.FM[value.currentString][value.currentCase]["digitA"] = value.currentDigitA;
            F.FM[value.currentString][value.currentCase]["digit"] = value.currentDigit;
            F.FM[value.currentString][value.currentCase]["intervale"] = {};
            F.FM[value.currentString][value.currentCase]["octave"] = value.currentOctave;
        });

        $.each(this.aYList, function (index, value) {
            F.FM[value][F.aXList[index]]["intervale"]={
                color:  aIntervaleColorList[index],
                intervale: F.aIntervaleList[index],
                wsname: F.aWsNameList[index]
            }
        });




        this.nbrString = this.FM.length;

        this.isAnOs = "no";
        if(!jQuery.inArray(0, this.aXList)){
            this.aXListNoOs = [];
            this.isAnOs = "yes";
            $.each(this.aXList,function(index,value){
                if(value>0){
                    F.aXListNoOs.push(value);
                }
            });
        }
        if(this.isAnOs == "yes"){
            this.maxX = Math.max.apply(Math,this.aXListNoOs);
            this.minX = Math.min.apply(Math,this.aXListNoOs);

        }else{
            this.maxX = Math.max.apply(Math,this.aXList);
            this.minX = Math.min.apply(Math,this.aXList);
        }

        if(this.maxX-this.minX+1 > this.deltaMin){
            this.deltaX = this.maxX - this.minX + 1;
        }else{
            this.deltaX = this.deltaMin;
        }

        this.draw();


        var recipientsArray = F.aYList.sort();
        var reportRecipientsDuplicate = [];
        for (var i = 0; i < recipientsArray.length - 1; i++) {
            if (recipientsArray[i + 1] == recipientsArray[i]) {
                reportRecipientsDuplicate.push(recipientsArray[i]);
            }
        }
        if(reportRecipientsDuplicate.length>0){
            var mode = "scale";
        }else{
            var mode = "chord";
        }

        $("#"+canvasId).bind( "click", function( e ) {

            console.log(this,aDigitAList);
            jnSynth.play(aDigitAList,mode);
        });
    },
    draw:function(){
        this.ctx.clearRect(0, 0, this.width, this.height);



            this.firstCase = this.minX;







        if(this.firstCase == 1){
            this.ctx.beginPath();
            this.ctx.strokeStyle='#000';
            this.ctx.lineWidth=1;
            this.ctx.moveTo(this.nX0 ,this.nY0    );
            this.ctx.lineTo(this.nX0 + this.nW ,this.nY0 )  ;
            this.ctx.stroke();

        }else{
            this.ctx.beginPath();
            this.ctx.fillStyle = "black";
            this.ctx.font="10px Arial";
            this.ctx.fillText(this.roman[this.firstCase],   0 , this.nY0+9);
        }

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




            if(this.FM[i][0]["intervale"].wsname){
                this.ctx.fillStyle = "#"+this.FM[i][0]["intervale"].color;
                this.ctx.fillRect(
                    this.nX0 + i * this.iX + this.iX/2 - this.dotSize/2,
                    this.nY0 - 16
                    ,this.dotSize,this.dotSize);

                this.ctx.font="10px Georgia";
                this.ctx.fillText(
                    this.FM[i][0]["intervale"].wsname,
                    this.nX0 + i * this.iX + this.iX/2 - this.dotSize/2,
                    this.nY0 - 17
                );
                this.ctx.fillText(
                    this.FM[i][0]["intervale"].intervale,
                    this.nX0 + i * this.iX + this.iX/2 - this.dotSize/2,
                    this.nY0 -4
                );
            }

            for(j=0;j<this.deltaX;j++){


                this.ctx.beginPath();
                this.ctx.strokeStyle='#444';
                this.ctx.lineWidth=.1;
                this.ctx.moveTo(this.nX0 ,this.nY0 + j * this.iY  );
                this.ctx.lineTo(this.nX0 + this.nW ,this.nY0 + j * this.iY)  ;
                this.ctx.stroke();


                if(this.FM[i][j+this.minX]["intervale"].wsname){

                    this.ctx.fillStyle = "#"+this.FM[i][j+this.minX]["intervale"].color;
                    this.ctx.fillRect(
                        this.nX0 + i * this.iX + this.iX/2 - this.dotSize/2,
                        this.nY0 + (j) * this.iY + this.iY/2 - this.dotSize/2
                        ,this.dotSize,this.dotSize);
                    this.ctx.font="10px Georgia";
                    this.ctx.fillText(
                        this.FM[i][j+this.minX]["intervale"].wsname,
                        this.nX0 + i * this.iX + this.iX/2 - 4,
                        this.nY0 + (j) * this.iY + this.iY/2 - 4
                    );
                    this.ctx.fillText(
                        this.FM[i][j+this.minX]["intervale"].intervale,
                        this.nX0 + i * this.iX + this.iX/2 - 4,
                        this.nY0 + (j) * this.iY + this.iY/2 +11
                    );
                }

            }
        }


    },


    roman:['0','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVII','XIX','XX','XXI','XXII','XXIII','XXIV'],
    deltaMin:3

}