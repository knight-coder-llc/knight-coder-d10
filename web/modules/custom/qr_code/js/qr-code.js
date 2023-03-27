(function ($, Drupal) {
    'use strict';
  
    Drupal.behaviors.qrCodeDownload = {
      attach: function (context, settings) {
        if (Drupal.behaviors.qrCodeDownload.exectued) return
        // Get the base64 encoded image data
        var imageData = drupalSettings.qrCode.imageData;
        console.log(imageData)
        // Create a data URI from the image data
        var dataURI = imageData;
        
        // Create a download link element
        var downloadLink = document.createElement('a');
        downloadLink.setAttribute('href', dataURI);
        downloadLink.setAttribute('target', '_blank');
        downloadLink.setAttribute('width', '300px');
        downloadLink.setAttribute('height', '300px');
        downloadLink.setAttribute('download', 'qr-code.svg');
        
        // get div element by class name qr-code-wrapper
        var qrCodeWrapper = document.getElementsByClassName('qr-code-wrapper')[0];
        //append download button to qr-code-wrapper
        qrCodeWrapper.innerHTML += '<button id="download-btn" class="text-center btn btn-primary" >Download</button>';
        
        // Attach a click event handler to the download button
        var downloadBtn = document.getElementById('download-btn');
        downloadBtn.addEventListener('click', function() {
          downloadLink.click();
        });

        Drupal.behaviors.qrCodeDownload.exectued = true;
      }
    };
  })(jQuery, Drupal, drupalSettings);