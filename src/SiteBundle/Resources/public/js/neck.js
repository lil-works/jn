var neck = {

    init:function(canvasId){



        Neck = this;

        this.getSession();

        this.canvasId = canvasId;
        this.c = document.getElementById(canvasId);
        this.width = this.c.width;
        this.height = this.c.height;
        this.ctx = this.c.getContext("2d");



        this.instrumentId=this.session["instrumentId"];
        this.sound=this.session["sound"];

        if(!this.instrumentId)
            this.instrumentId = 1;

        if(!this.sound)
            this.sound = "piano";

        this.setControls();
        this.getInstrument( this.instrumentId );

        return this;
    },

    insertAllRootScale:function(){
        $.each(Neck.rsBasket,function(index,value){
            var splited =  value.split("_");
            Neck.insertRootScale(splited[0],splited[1]);
        });

    },
    insertRootScale:function(r,s){

        var ajax_neck_rootScale = Routing.generate('ajax_neck_rootScale');
        Neck.rsBasket[0] = r+"_"+s;

        var request = $.ajax({
            url: ajax_neck_rootScale,
            method: "POST",
            data: {i: Neck.instrumentId, r: r, s: s},
            dataType: "html",
            async: false
        });


        request.done(function (msg) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                Neck.formatedMatrice[value.currentString][value.currentCase].intervale[r + "_" + s] = {intervaleName:value.currentIntervale,toneName:value.wsName};
            });
            Neck.drawNeck();

        });

    },
    insertFingerings:function(jsonobj){
        this.fBasket = $.parseJSON(jsonobj.replace(/&quot;/g, '\"'));

    },

    getInstrument:function(instrument_id){
        var ajax_neck_instrument = Routing.generate('ajax_neck_instrument');
        this.cleanSelection();
        var request = $.ajax({
            url: ajax_neck_instrument,
            method: "POST",
            data: { instrument_id : instrument_id },
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {

            JSONNodes = msg;
            this.matrice = $.parseJSON(JSONNodes.replace(/&quot;/g, '\"'));
            formatedMatrice = [];
            Neck.formatedMatrice = [];
            for(i=0;i<this.matrice.length;i++){
                if(!formatedMatrice[this.matrice[i].currentString]){ formatedMatrice[this.matrice[i].currentString] = [];}
                if(!formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase]){formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase] = [];}
                formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase] = {
                    "case": this.matrice[i].currentCase,
                    "string": this.matrice[i].currentString,
                    "digit": this.matrice[i].currentDigit,
                    "digitA": this.matrice[i].currentDigitA,
                    "infoTone": this.matrice[i].currentInfoTone,
                    "intervale": {},
                    "octave": this.matrice[i].currentOctave
                };
            }
            Neck.matrice = msg;
            Neck.formatedMatrice = formatedMatrice;

            Neck.drawNeck();
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

    },
    cleanIntervalesInFormatedMatrice:function(index){
        /*
         * r_s
         * 21_1
         */
        if(!index){
            $.each(Neck.formatedMatrice,function(k1,v1){
                $.each(Neck.formatedMatrice[k1],function(k2,v2){
                    $.each(Neck.formatedMatrice[k1][k2].intervale,function(k3,v3){
                        delete Neck.formatedMatrice[k1][k2].intervale[k3];
                    });
                });
            });
        }else{
            $.each(Neck.formatedMatrice,function(k1,v1){
                $.each(Neck.formatedMatrice[k1],function(k2,v2){
                    if(Neck.formatedMatrice[k1][k2].intervale[index])
                        delete Neck.formatedMatrice[k1][k2].intervale[index];
                });
            });
        }
    },
    setControls:function(){

        $( "#inlaysSelector" ).change(function() {
            if(this.checked){
                Neck.showInlays = true;
            }else{
                Neck.showInlays = false;
            }
            Neck.drawNeck();
        });
        $( "#fretNumSelector" ).change(function() {
            if(this.checked){
                Neck.showFretNum = true;
            }else{
                Neck.showFretNum = false;
            }
            Neck.drawNeck();
        });
        this.fillInstrumentSelector();
        $( "#instrumentSelector" ).change(function() {
            //Neck.getInstrument(this.value);
            Neck.instrumentId =this.value;
            Neck.storeSession();
            location.reload();

            //Neck.insertAllRootScale();
        });

        $('#soundSelector').val(Neck.sound);
        $( "#soundSelector" ).change(function() {
            Neck.sound = this.value;
            Neck.storeSession();
            Neck.drawNeck();
        });


        $( "#nbrCasesMax" ).click(function() {
            Neck.displayedCase = Neck.displayedCaseMax
            Neck.drawNeck();
        });
        $( "#nbrCasesReset" ).click(function() {
            Neck.displayedCase = Neck.displayedCaseMin
            Neck.drawNeck();
        });
        $( "#nbrCasesAdd" ).click(function() {
            if(Neck.displayedCase<Neck.displayedCaseMax)
                Neck.displayedCase++;
            Neck.drawNeck();
        });
        $( "#nbrCasesRemove" ).click(function() {
            if(Neck.displayedCase>Neck.displayedCaseMin)
                Neck.displayedCase--;
            Neck.drawNeck();
        });


        $( "#clearRootScaleSelection" ).click(function() {
            Neck.cleanIntervalesInFormatedMatrice();
            Neck.rsBasket = [];
            Neck.drawNeck();
        });


        if(Neck.scBasket.length>0){
            $( "#clearSelection").show();
        }else{
            $( "#clearSelection").hide();
        }

        $( "#clearSelection" ).click(function() {
            Neck.scBasket = [];
            Neck.drawNeck();
        });
        $( "#fingeringSelection" ).click(function() {
            Neck.fingeringAction();
        });


        $( "#searchScaleFromSelection" ).click(function() {
            var ajax_neck_searchRootScaleByDigits = Routing.generate('ajax_neck_searchRootScaleByDigits');

            var digits = [];
            $.each(Neck.scBasket, function(key,value){
                var splited = value.split("_");
                if($.inArray(Neck.formatedMatrice[splited[0]][splited[1]].digit,digits) < 0)
                    digits.push(Neck.formatedMatrice[splited[0]][splited[1]].digit);

            });
            var request = $.ajax({
                url: ajax_neck_searchRootScaleByDigits,
                method: "POST",
                data: { digits:digits },
                dataType: "html",
                async: false
            });


            request.done(function (msg) {

                $("#neckResults").empty();

                var html="<h2>Results</h2><ul id=\"neckResultsList\"></ul>";
                $("#neckResults").append(html);
                $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                    console.log(index,value);
                    var datas = [];
                    var nameList = value.intervaleNameList.split(",");
                    var deltaList = value.intervaleDeltaList.split(",");
                    var colorList = value.intervaleColorList.split(",");

                    for(i=0;i<nameList.length;i++){
                        datas[deltaList[i]] = ["C",nameList[i],colorList[i]];
                    }
                    var site_rootscale_index = Routing.generate('site_rootscale_index',{scale:value.scaleName,root:value.wsName});


                    html="<li><div class=\"titleInVignette\"><a href=\""+site_rootscale_index+"\">"+value.rootInfoTone+" "+value.scaleName+"</a></div><div><canvas id=\"root_"+value.rootInfoTone+"_scale_"+value.scaleId+"\" width=\"180\" height=\"180\"></canvas></div></li>";
                    $("#neckResultsList").append(html);

                    new diagram("root_"+value.rootInfoTone+"_scale_"+value.scaleId,datas);
                });




            });


            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });


        });

        this.fillRootScaleSelector();

        $( "#addRootScale" ).click(function() {
            Neck.rsAction();
        });

    },

        fingeringAction:function(){
            var ajax_neck_fingering = Routing.generate('ajax_neck_fingering');
            var request = $.ajax({
                url: ajax_neck_fingering,
                method: "POST",
                data: {i: Neck.scBasket},
                dataType: "html",
                async: false
            });


            request.done(function (msg) {

                console.log(msg);

            });


            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });

        },
        rsAction:function(){

            var ajax_neck_rootScale = Routing.generate('ajax_neck_rootScale');
            var r = $("#rootSelector").attr('option','selected').val();
            var s = $("#scaleSelector").attr('option','selected').val();

            var i = $.inArray(r+"_"+s, Neck.rsBasket )
            if(i>=0){
                return false;
            }else{
                Neck.rsBasket.push(r+"_"+s);
            }

            $.each(Neck.rsBasket, function( index, value ) {

                var splitedValue = value.split("_");
                var r =  splitedValue[0];
                var s =  splitedValue[1];
                var newRs = true;

                for(i=0;i<Neck.formatedMatrice.length;i++){
                    for(j=0;j<Neck.formatedMatrice[i].length;j++){
                        if(Neck.formatedMatrice[i][j].intervale[r+"_"+s])
                            newRs = false;
                        break;
                    }
                }
                if(newRs) {
                    var request = $.ajax({
                        url: ajax_neck_rootScale,
                        method: "POST",
                        //data: {i: $("#instrumentSelector").attr("option", "selected").val(), r: r, s: s},
                        data: {i: Neck.instrumentId, r: r, s: s},
                        dataType: "html",
                        async: false
                    });


                    request.done(function (msg) {

                        $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                            Neck.formatedMatrice[value.currentString][value.currentCase].intervale[r + "_" + s] = { intervaleName:value.currentIntervale , toneName:value.wsName };
                        });

                    });


                    request.fail(function (jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                }
            });
            Neck.drawNeck();
        },
    fillRootScaleSelector:function(){
        var ajax_neck_scale = Routing.generate('ajax_neck_scale');
        var request = $.ajax({
            url: ajax_neck_scale,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });
        request.done(function( msg ) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#scaleSelector').append($('<option>', {
                    value: value.id,
                    text: value.name
                }));
            });
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        var ajax_neck_root = Routing.generate('ajax_neck_root');
        var request = $.ajax({
            url: ajax_neck_root,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });
        request.done(function( msg ) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#rootSelector').append($('<option>', {
                    value: value.id,
                    text: value.name
                }));
            });
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });




        return true;
    },

    storeSession:function(){
        var ajax_neck_session_set = Routing.generate('ajax_neck_session_set');
        var request = $.ajax({
            url: ajax_neck_session_set,
            method: "POST",
            data: {instrumentId: Neck.instrumentId, sound: Neck.sound},
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {
            return true;
        });


        request.fail(function( jqXHR, textStatus ) {
            return false
        });
    },
    getSession:function(){
        var ajax_neck_session_get = Routing.generate('ajax_neck_session_get');

        var request = $.ajax({
            url: ajax_neck_session_get,
            method: "POST",
            data: {},
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {
            Neck.session = Array();
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                Neck.session[index]=value;
            });

        });


        request.fail(function( jqXHR, textStatus ) {
            return false
        });
    },
    fillInstrumentSelector:function(){
        var ajax_neck_instruments = Routing.generate('ajax_neck_instruments');
        var request = $.ajax({
            url: ajax_neck_instruments,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {

            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#instrumentSelector').append($('<option>', {
                    value: value.id ,
                    text: value.name
                }));

            });

            $('#instrumentSelector').val(Neck.instrumentId);

        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        return true;
    },
    drawNeck:function(){


        jnSynth.init(this.sound);

        this.ctx.clearRect(0, 0, this.width, this.height);

        var caseW = this.width/(this.displayedCase);
        var caseH = this.height/this.formatedMatrice.length;


        var rects = [];



        for(i=0;i<this.formatedMatrice.length;i++){

            this.ctx.beginPath();
            this.ctx.strokeStyle='gold';
            this.ctx.lineWidth=100/this.formatedMatrice[i][0].digitA;
            this.ctx.moveTo( caseW, this.height - i*caseH - caseH/2);
            this.ctx.lineTo(this.width, this.height - i*caseH - caseH/2)  ;
            this.ctx.stroke();

            this.ctx.fillStyle = "#666";
            this.ctx.font="9px Georgia black";

            this.ctx.fillText(this.formatedMatrice[i][0].infoTone+this.formatedMatrice[i][0].octave,   3, Neck.height-i*caseH -caseH/2 +2 );

            for(j=0;j<this.displayedCase+1;j++){
                rects.push({x: j*caseW, y:(this.height-caseH) - i*caseH, w: caseW, h: caseH , case:j,string:i});
                // dont draw in case 0
                if(j>0){
                    this.ctx.beginPath();
                    this.ctx.strokeStyle='silver';
                    this.ctx.lineWidth=1;
                    this.ctx.moveTo( j*(caseW), (caseH/2)-5);
                    this.ctx.lineTo(j*(caseW), 5 + this.height - caseH/2)  ;
                    this.ctx.stroke();
                    this.ctx.rect(j*caseW-2,i*caseH-2,caseW-2,caseH-2);
                    if(i==0 && Neck.showFretNum){
                        Neck.ctx.fillStyle = "#666";
                        Neck.ctx.font="10px Georgia";
                        Neck.ctx.fillText(this.roman[j],   j*caseW , Neck.height-i*caseH  -5);
                    }
                }


                var len = $.map(this.formatedMatrice[i][j].intervale, function(n, i) { return i; }).length;
                k=1;
                $.each(this.formatedMatrice[i][j].intervale, function(key, value)
                {
                    var rsBasketKey = $.inArray(key,Neck.rsBasket);
                    Neck.ctx.fillStyle = Neck.rsColors[rsBasketKey];
                    Neck.ctx.fillRect(   j*caseW + caseW*k*1/(1+len)  ,Neck.height-i*caseH -caseH/2 ,5,5);
                    Neck.ctx.font="12px Georgia";
                    Neck.ctx.fillText(value.intervaleName,   j*caseW + caseW*k*1/(1+len), Neck.height-i*caseH -caseH/2 -5);
                    Neck.ctx.font="10px Georgia";
                    Neck.ctx.fillText(value.toneName,   j*caseW + caseW*k*1/(1+len), Neck.height-i*caseH -caseH/2 +15);
                    k++;
                });

            }




        }

        $.each(this.fBasket, function(key, value)
        {


                var yvalues = value.yList.split(",");
                var xvalues = value.xList.split(",");
                var wsname = value.wsNameList.split(",");
                var intervals= value.intervaleList.split(",");

            $.each(yvalues, function(yk, vk)
            {

                Neck.ctx.beginPath();
                Neck.ctx.arc(xvalues[yk]*caseW + caseW/2, Neck.height-yvalues[yk]*caseH -caseH/2, (caseW/2)-8, 0, 2 * Math.PI, false);
                Neck.ctx.fillStyle = Neck.fColors[key];
                Neck.ctx.fill();
            })


        });


        this.ctx.beginPath();
        this.ctx.strokeStyle='#000';
        this.ctx.lineWidth=1;
        this.ctx.moveTo( caseW-3, (caseH/2));
        this.ctx.lineTo(caseW-3, this.height - caseH/2)  ;
        this.ctx.stroke();

        if(this.showInlays){
            $.each(this.inlays, function(key, value)
            {
                var centerX = value*caseW + caseW/2;
                var centerY = Neck.height/2;
                var radius = caseW/10;
                if(value == 12){
                    var radius = caseW/12;
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY-caseH, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fillStyle = "rgba(88, 163, 127,.2)";
                    Neck.ctx.fill();
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY+caseH, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fill();
                }else{
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fillStyle = "rgba(88, 163, 127,.2)";
                    Neck.ctx.fill();
                }

            });
        }
        $("#"+this.canvasId).unbind();
        $("#"+this.canvasId).bind( "click", function( e ) {
            var rect = Neck.collides(rects, e.offsetX, e.offsetY);
            if (rect) {
                Neck.selectStringCase(rect.string , rect.case );
                jnSynth.play(Array(Neck.formatedMatrice[rect.string][rect.case].digitA ), 'chord');
            }
        });

        // SC SELECTION
        $("#currentSelectionUl").empty();
        $.each(this.scBasket, function( index, value ) {
            var splitedValue = value.split("_");
            var s =  splitedValue[0];
            var c =  splitedValue[1];

            Neck.ctx.fillStyle = "rgba(10, 10, 10, 1)";
            Neck.ctx.fillRect( caseW/2 + c*caseW,Neck.height-s*caseH -caseH/2 - 3,6,6);

            $("#currentSelectionUl").append("<li>[ "+s+"-"+c+" ]"+Neck.formatedMatrice[s][c].infoTone+Neck.formatedMatrice[s][c].octave+"</li>");
        });

        if(Neck.scBasket.length>0){
            $( "#clearSelection").show();
            $( "#searchScaleFromSelection").show();
        }else{
            $( "#clearSelection").hide();
            $( "#searchScaleFromSelection").hide();
        }
        // RS SELECTION
        $("#currentRootScaleSelectionUl").empty();
        $.each(this.rsBasket, function( index, value ) {
            var rsBasketKey = $.inArray(value, Neck.rsBasket);

            var splitedValue = value.split("_");
            var rootName = $("#rootSelector option[value="+splitedValue[0]+"]").text()
            var scaleName = $("#scaleSelector option[value="+splitedValue[1]+"]").text()
            $("#currentRootScaleSelectionUl").append("<li id=\"rootScale-" + value + "\" style=\"color:" + Neck.rsColors[rsBasketKey] + "\">"+rootName+" "+scaleName+"</li>");
            $("#rootScale-" + value).click(function () {
                Neck.cleanIntervalesInFormatedMatrice(value);
                Neck.rsBasket.splice(rsBasketKey, 1);
                Neck.drawNeck();
            });
        });



    },
    cleanSelection:function () {
        $("#currentSelectionUl").empty();
        this.scBasket = [];
    },
    selectStringCase:function (s,c) {
        var i = $.inArray(s+"_"+c, this.scBasket )
        if(i>=0)
            this.scBasket.splice(i,1);
        else
            this.scBasket.push(s+"_"+c);

        this.drawNeck();
    },
    /*
     selectRootScaleCase:function (r,s) {
     var i = $.inArray(r+"_"+s, this.rsBasket )
     if(i>=0)
     this.rcBasket.splice(i,1);
     else
     this.rcBasket.push(r+"_"+s);

     this.drawNeck();
     },*/
    collides:function (rects, x, y) {
        var isCollision = false;
        for (var i = 0, len = rects.length; i < len; i++) {
            var left = rects[i].x, right = rects[i].x+rects[i].w;
            var top = rects[i].y, bottom = rects[i].y+rects[i].h;
            if (right >= x
                && left <= x
                && bottom >= y
                && top <= y) {
                isCollision = rects[i];
            }
        }
        return isCollision;
    },
    session:Array,
    instrument:null,
    instrumentId:null,
    formatedMatrice:null,
    matrice:null,
    scBasket:[],
    rsBasket:[],
    showInlays:true,
    inlays:[3,5,7,9,12,15,17,19,21,24],
    showFretNum:true,
    roman:['0','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVII','XIX','XX','XXI','XXII','XXIII','XXIV'],
    sound:"piano",
    rsColors:["hsl(265, 33%, 67%)", "hsl(344, 95%, 77%)","hsl(159, 96%, 63%)","hsl(27, 76%, 73%)","hsl(359, 96%, 90%)"],
    fColors:["rgba(100,30,20,.1)", "rgba(87,120,20,.1)", "rgba(30,100,20,.1)","rgba(20,30,100,.1)","rgba(100,30,100,.1)","rgba(100,100,20,.1)"],
    firstCase:1,
    displayedCase:5,
    displayedCaseMin:5,
    displayedCaseMax:17
};