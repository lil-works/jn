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



        var aXList = this.datas.xList.split(",");
        var aIntervaleList = this.datas.intervaleList.split(",");
        var aWsNameList = this.datas.wsNameList.split(",");
        $.each(this.datas.yList.split(","), function (index, value) {


            Fingering.formatedMatrice[value][aXList[index]]["intervale"]={interval:aIntervaleList[index],wsname:aWsNameList[index]}
        });
        this.maxX=Math.max.apply(Math,aXList); // 3
        this.minX=Math.min.apply(Math,aXList); // 1
        if(this.maxX-this.minX > this.deltaMin){
            this.deltaX = this.maxX-this-minX;
        }else{
            this.deltaX = this.deltaMin;
        }

        this.nbrString = Fingering.formatedMatrice.length;


        this.draw();
        return this;

    },
    draw:function(){


        this.ctx.clearRect(0, 0, this.width, this.height);

        var caseW = this.width;
        var caseH = this.height/this.deltaX;





        for(i=0;i<this.nbrString.length;i++){

            this.ctx.beginPath();
            this.ctx.strokeStyle='gold';
            this.ctx.lineWidth=1;
            console.log(i * caseW, 0,i * caseW, caseH);
            this.ctx.moveTo( i * caseW, 0);
            this.ctx.lineTo( i * caseW, caseH)  ;
            this.ctx.stroke();


            for(j=this.minX;j<this.deltaX+1;j++){





            }




        }





    },
    formatedMatrice:null,
    nbrString:0,
    minX:0,
    minY:0,
    deltaMin:4,
    deltaX:5


};