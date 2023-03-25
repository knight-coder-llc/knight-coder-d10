(function ($, Drupal) {
    'use strict';
  
    Drupal.behaviors.qrCodeDownload = {
      attach: function (context, settings) {
        // Get the base64 encoded image data
        var imageData = "{{"+ image_data +"}}";
        console.log('attached', imageData);
        // Create a data URI from the image data
        var dataURI = imageData;
  
        // Create a download link element
        var downloadLink = $('<a>', {
          href: dataURI,
          download: 'qr-code.png'
        });
  
        // Create a download button element
        var downloadBtn = $('<button>', {
          id: 'download-btn',
          text: 'Download'
        });
  
        // Get the QR code wrapper element
        var qrCodeWrapper = $('.qr-code-wrapper', context);
  
        // Append the download button to the QR code wrapper
        downloadBtn.appendTo(qrCodeWrapper);
  
        // Attach a click event handler to the download button
        downloadBtn.on('click', function () {
          downloadLink.get(0).click();
        });
      }
    };
  })(jQuery, Drupal);