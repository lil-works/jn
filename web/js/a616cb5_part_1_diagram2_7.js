function diagram2(canvasId,datas) {



    Diagram2 = this;

    this.c = document.getElementById(canvasId);
    this.ctx = this.c.getContext("2d");

    this.margin=5;
    this.width = this.c.height-2*this.margin;
    this.height = this.c.height-2*this.margin;

    this.radius =  (this.width-50)/2;
    this.point_size = 2;
    this.center_x = this.width/2;
    this.center_y = this.height/2;
    this.points = [];
    this.rects = [];

    this.intervales = [];
    this.datas = datas;

    this.tones = Array("C","C#/Db","D","D#/Eb","E","F","F#/Gb","G","G#/Ab","A","A#/Bb","B");





    this.drawPoint = function(angle,distance,label,pseudoCase){
        var x = this.center_x + this.radius * Math.cos(-angle*Math.PI/180) * distance;
        var y = this.center_y + this.radius * Math.sin(-angle*Math.PI/180) * distance;
        var x2 = this.center_x + this.radius * Math.cos(-angle*Math.PI/180) * distance*1.1;
        var y2 = this.center_y + this.radius * Math.sin(-angle*Math.PI/180) * distance*1.1;

        this.ctx.beginPath();

        this.ctx.font = "11px Arial";

        this.ctx.fillText(label, x2  ,y2 );


        this.ctx.beginPath();
        this.ctx.fillStyle = 'black';
        this.ctx.arc(x, y, this.point_size, 0, 2 * Math.PI);
        this.ctx.fill();


        this.rects.push({x: x, y:y , w: 30, h: 10 , case:pseudoCase,string:0});



        return [x,y];

    }

    this.draw = function(){
        this.ctx.beginPath();
        this.ctx.arc(this.center_x, this.center_y, this.radius, 0, 2 * Math.PI);
        this.ctx.stroke();

        for(i=0;i<12;i++){

            Diagram2.drawPoint(360 * i / 12 + 3*360/12,1,"");
            //if(Diagram2.datas[i]){
                var p = Diagram2.drawPoint(- 360 * i / 12 +  3*360/12,1,this.tones[i] ,i  );
                Diagram2.points.push([p[0],p[1]]);
            //}

        }
        /*
        $.each(Diagram.points,function(index1,value1){
            $.each(Diagram.points,function(index2,value2){
                Diagram2.ctx.beginPath();
                Diagram2.ctx.moveTo(value1[0],value1[1]);
                Diagram2.ctx.lineTo(value2[0],value2[1]);
                Diagram2.ctx.strokeStyle = 'rgba(0,0,0,.2)';
                Diagram2.ctx.stroke();
            });
        });
*/

    }

    Diagram2.draw();

    console.log(this);

}