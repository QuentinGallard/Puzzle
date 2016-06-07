(function () {

    var cell = $('.cell img');

    cell.each(function (index, item) {
        var rotation = getRandomInt(0, 3) * 90;
        $(item).css('transform', 'rotate(' + rotation + 'deg)');
    });

    cell.on('click', function (e) {
        //When you click really fast, getAngleFromElement gives you the angle during the rotation. So it is not the good angle.
        var angle = Math.floor(getAngleFromElement(this) / 90) * 90;
        var newAngle = (angle + 90);
        $(this).velocity({rotateZ: newAngle}, {easing: 90, duration: 100});
    });

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    var getAngleFromElement = function (element) {

        var el = $(element);

        var tr = el.css("-webkit-transform") ||
            el.css("-moz-transform") ||
            el.css("-ms-transform") ||
            el.css("-o-transform") ||
            el.css("transform") ||
            "fail...";

        var values = tr.split('(')[1];
        values     = values.split(')')[0];
        values     = values.split(',');

        return parseInt(values[0].replace('deg', ''));
    };

})();