var page = require('webpage').create(),
    address, output, size;

if (phantom.args.length != 9) {
    console.log('Missing arguments.');
    phantom.exit();
} else {
    address = phantom.args[0];
    output = phantom.args[1];

    //set viewpoint
    vpW = phantom.args[2];
    vpH = phantom.args[3];
    if (vpW > 0 && vpH > 0) page.viewportSize = { width: vpW, height: vpH };

    //set clipping
    clipT = phantom.args[4];
    clipL = phantom.args[5];
    clipW = phantom.args[6];
    clipH = phantom.args[7];
    if (clipW > 0 && clipH > 0) page.clipRect = { top: clipT, left: clipL, width: clipW, height: clipH};

    delay = phantom.args[8];

    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('Unable to load the address!');
            phantom.exit();
        } else {
            window.setTimeout(function () {
                page.render(output);
                phantom.exit();
            }, delay);
        }
    });
}