function collides(rects, x, y) {
    var isCollision = false;
    for (var i = 0, len = rects.length; i < len; i++) {
        var left = rects[i].x, right = rects[i].x + rects[i].w;
        var top = rects[i].y, bottom = rects[i].y + rects[i].h;
        if (right >= x
            && left <= x
            && bottom >= y
            && top <= y) {
            isCollision = rects[i];
        }
    }
    return isCollision;
}




var fingeringPicker = {


    init:function(canvasId){

        $('form[name="fingering"]').hide();

        FP = this;

        this.canvasId = canvasId;
        this.c = document.getElementById(canvasId);
        this.width = this.c.width;
        this.height = this.c.height;
        this.ctx = this.c.getContext("2d");

        this.strings = 10;
        this.cases = 10;
        this.selection = [];

        this.ctx = this.c.getContext("2d");


        this.draw();
        this.controls();
    },
    controls:function(){

        $("#myFP_clearSelection").hide();
        $("#myFP_addFingering").hide();

        $("#myFPControls").append($("#fingering label[for='fingering_description']").clone());
        $("#myFPControls").append($("#fingering_description").clone().prop('class', 'form-control' ));
        $("#myFPControls").append($("#fingering label[for='fingering_difficulty']").clone());
        $("#myFPControls").append($("#fingering_difficulty").clone().prop('class', 'form-control' ));
        $("#myFPControls").append($("#fingering label[for='fingering_arpeggio']").clone());
        $("#myFPControls").append($("#fingering_arpeggio").clone());


        $( "#myFPControls #fingering_description" ).change(function() {
            $( "#fingering #fingering_description").val($(this).val());
        });

        $( "#myFPControls #fingering_difficulty" ).change(function() {
            $( "#fingering #fingering_difficulty").val($(this).val());
        });

        $( "#myFPControls #fingering_arpeggio" ).change(function() {
            $( "#fingering #fingering_arpeggio").val($(this).val());
        });


        FP.map = [];
        FP.rmap = [];
        $("#fingering_fingers").children("div").each(
            function(){
                $(this).children("div").each(
                    function(){
                        var aid = this.id.split("fingering_fingers_");
                        if(aid[1]){
                            FP.map[aid[1]] =  $("#fingering_fingers_"+aid[1]+"_y").val() + "_" + $("#fingering_fingers_"+aid[1]+"_x").val();
                            FP.rmap[$("#fingering_fingers_"+aid[1]+"_y").val() + "_" + $("#fingering_fingers_"+aid[1]+"_x").val()] =  aid[1];
                        }
                    }
                );
            }
        );



    },
    draw:function(){
        this.ctx.clearRect(0, 0, this.width, this.height);

        var caseW = this.width/this.cases;
        var caseH = this.height/this.strings;


        FP.rects = [];
        for(s = 0 ; s < this.strings ; s++ ){

            for(c = 0 ; c < this.cases ; c++ ){
                FP.rects.push({x: c*caseW, y:(this.height-caseH) - s*caseH, w: caseW, h: caseH , case:c,string:s});
                this.ctx.beginPath();
                this.ctx.strokeStyle='grey';
                this.ctx.lineWidth=1;
                this.ctx.moveTo( caseW * c , 0);
                this.ctx.lineTo( caseW * c, this.height)  ;
                this.ctx.stroke();
            }

            this.ctx.beginPath();
            this.ctx.strokeStyle='gold';
            this.ctx.lineWidth=2;
            this.ctx.moveTo( caseW, this.height - s*caseH - caseH/2);
            this.ctx.lineTo(this.width, this.height - s*caseH - caseH/2)  ;
            this.ctx.stroke();
        }

        $("#myFPControls .sub_finger").remove();
        if(this.selection.length >= FP.minSelection){
            $("#myFP_clearSelection").show();
            $("#myFP_addFingering").show();
        }else{
            $("#myFP_clearSelection").hide();
            $("#myFP_addFingering").hide();
        }

        $.each(this.selection, function( index, value ) {
            var splitedValue = value.split("_");
            var s =  splitedValue[0];
            var c =  splitedValue[1];
            FP.ctx.fillStyle = "rgba(10, 10, 10, 1)";
            FP.ctx.fillRect( caseW/2 + c*caseW,FP.height-s*caseH -caseH/2 - 3,6,6);

            $("#myFPControls").append('<div class="sub_finger" id="sub_'+index+'"><h3>'+s+'_'+c+'</h3></div>');
            $("#sub_"+index).append($("label [for='fingering_fingers_"+FP.rmap[s+"_"+c]+"_rh']").clone());
            $("#sub_"+index).append($("#fingering_fingers_"+FP.rmap[s+"_"+c]+"_rh").clone());
            $("#sub_"+index).append($("label [for='fingering_fingers_"+FP.rmap[s+"_"+c]+"_lh']").clone());
            $("#sub_"+index).append($("#fingering_fingers_"+FP.rmap[s+"_"+c]+"_lh").clone());




            $("#fingering_fingers_"+FP.rmap[s+"_"+c]+"_played").attr('checked' , true);
            $( "#fingering_fingers_"+FP.rmap[s+"_"+c]+"_rh" ).change(function() {
                $("#fingering_fingers #fingering_fingers_"+FP.rmap[s+"_"+c]+"_rh").val($(this).val());
            });
            $( "#fingering_fingers_"+FP.rmap[s+"_"+c]+"_lh" ).change(function() {
                $("#fingering_fingers #fingering_fingers_"+FP.rmap[s+"_"+c]+"_lh").val($(this).val());
            });


            $("#myFP_addFingering").click(function(){
                console.log($("form"));
                $("form").submit();
            });
        });

        $("#"+FP.canvasId).unbind();
        $("#"+FP.canvasId).bind( "click", function( e ) {

            var rect = collides(FP.rects, e.offsetX, e.offsetY);

            if (rect) {
                var i = $.inArray( rect.string+"_"+rect.case , FP.selection );
                if(i>=0) {
                    FP.selection.splice(i, 1);
                    $("#fingering_fingers_"+FP.rmap[rect.string+"_"+ rect.case]+"_played").attr('checked' , false);
                }else {
                    FP.selection.push(rect.string + "_" + rect.case);
                }






                FP.draw();



            }

        });
    },

    minSelection:1


}