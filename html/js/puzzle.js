(function (){
    
    var cell = $('.cell');

    console.log('I am ready');




    cell.each( function(index, item){
        var rotation = getRandomInt(0,4) * 90;
        // console.log('cell %d is: %o', index, $(this).children('img'));
        // console.log('this cell must be turn to '+ rotation+'deg');
        // console.log(this);
        // console.log(index);
        // console.log(item);
        $(item).css('transform', 'rotate('+rotation+'deg)');

        // item.animate({
        //     rotateZ: rotation+'deg'
        // }, 500, 'ease-out');
    });

    cell.on('click', function(e){
        $(this).velocity({rotateZ: +90});
    });

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

})();