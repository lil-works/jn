function diagram(canvasId,datas) {


    Diagram = this;
    this.c = document.getElementById(canvasId);
    this.ctx = this.c.getContext("2d");

    this.width = this.c.width;
    this.height = this.c.height;

    this.radius = (this.width - 50) / 2;
    this.point_size = 2;
    this.center_x = this.width / 2;
    this.center_y = this.height / 2;
    this.points = [];

    this.intervales = [];
    this.datas = datas;


    this.init = function () {
        this.draw();
    }


    this.drawPoint = function (angle, distance, label, color) {
        var x = this.center_x + this.radius * Math.cos(-angle * Math.PI / 180) * distance;
        var y = this.center_y + this.radius * Math.sin(-angle * Math.PI / 180) * distance;
        var x2 = this.center_x + this.radius * Math.cos(-angle * Math.PI / 180) * distance * 1.2;
        var y2 = this.center_y + this.radius * Math.sin(-angle * Math.PI / 180) * distance * 1.2;

        this.ctx.beginPath();

        this.ctx.font = "12px Arial";
        this.ctx.fillStyle = '#' + color;
        this.ctx.fillText(label, x2, y2);


        this.ctx.beginPath();
        this.ctx.fillStyle = 'black';
        this.ctx.arc(x, y, this.point_size, 0, 2 * Math.PI);
        this.ctx.fill();

        return [x, y];

    }

    this.draw = function () {
        this.ctx.beginPath();
        this.ctx.arc(this.center_x, this.center_y, this.radius, 0, 2 * Math.PI);
        this.ctx.stroke();

        for (i = 0; i < 24; i++) {

            Diagram.drawPoint(360 * i / 12 + 3 * 360 / 12, 1, "");
            if (Diagram.datas[i]) {
                var p = Diagram.drawPoint(-360 * i / 12 + 3 * 360 / 12, 1, Diagram.datas[i][1], Diagram.datas[i][2]);
                Diagram.points.push([p[0], p[1]]);
            }

        }

        $.each(Diagram.points, function (index1, value1) {
            $.each(Diagram.points, function (index2, value2) {
                Diagram.ctx.beginPath();
                Diagram.ctx.moveTo(value1[0], value1[1]);
                Diagram.ctx.lineTo(value2[0], value2[1]);
                Diagram.ctx.strokeStyle = 'rgba(0,0,0,.2)';
                Diagram.ctx.stroke();
            });
        });


    }

    this.init();
}