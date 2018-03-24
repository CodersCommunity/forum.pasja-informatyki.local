(function() {

    var iconsFolderURL = '/qa-theme/SnowFlat/icons/';
    var icons = [
        'code1.png',
        'net1.png',
        'os1.png',
        'eth1.png',
        'ph1.png',
        'key1.png',
        'stu1.png',
        'egz1.png',
        'new1.png',
        'tut1.png',
        'for1.png',
        'tea1.png',
        'ruler1.png',
        'note1.png',
        'brush1.png',
        'off1.png'
    ];

    window.addEventListener('load', function() {
        
        icons.forEach(function(icon) {
            var asyncImg = new Image();
            asyncImg.src = iconsFolderURL + icon;
        });

    });
    
})();