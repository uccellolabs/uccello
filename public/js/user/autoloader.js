(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{3:function(t,e,i){t.exports=i("SrVw")},SrVw:function(t,e,i){"use strict";i.r(e);var a=i("urRO"),n=i.n(a);function o(t,e){for(var i=0;i<e.length;i++){var a=e[i];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}var r=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.addAvatarTypeListener(),this.addAvatarSubmitButtonListener(),this.initFileUpload(),this.initAvatarCropper()}var e,i,a;return e=t,(i=[{key:"addAvatarTypeListener",value:function(){var t=$("#change_avatar");$('input[name="avatar_type"]',t).on("change",function(e){var i=e.currentTarget;switch($(".info span",t).hide(),$(".profile-image img, .profile-image div").hide(),$(".image-upload").hide(),$(i).val()){case"initials":$(".info span.avatar-initials",t).show(),$(".profile-image .initials").show();break;case"gravatar":$(".info span.avatar-gravatar",t).show(),$(".profile-image .gravatar").show();break;case"image":$(".info span.avatar-image",t).show(),$(".profile-image .image").show(),$(".image-upload").show()}})}},{key:"addAvatarSubmitButtonListener",value:function(){var t=this;$("#avatar-submit").on("click",function(e){$("#avatar-input").val(t.canvasDataUrl),$("#change_avatar form").submit()})}},{key:"initFileUpload",value:function(){var t=this;$("#avatar-file").on("change",function(e){var i=e.currentTarget.files;if(i&&i.length>0){var a=i[0],n=new FileReader;n.onload=function(e){t.cropper.replace(n.result)},n.readAsDataURL(a)}}),$("#upload-link").on("click",function(t){t.preventDefault(),$("#avatar-file").click()})}},{key:"initAvatarCropper",value:function(){var t=this,e=document.getElementById("avatar-image-preview");this.cropper=new n.a(e,{aspectRatio:1,autoCropArea:1,minContainerWidth:250,minContainerHeight:250,zoomable:!1,crop:function(e){var i=t.cropper.getCroppedCanvas();t.canvasDataUrl=i.toDataURL(),$("img.image").prop("src",t.canvasDataUrl)}})}}])&&o(e.prototype,i),a&&o(e,a),t}();function h(t,e){for(var i=0;i<e.length;i++){var a=e[i];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.lazyLoad()}var e,i,a;return e=t,(i=[{key:"lazyLoad",value:function(){switch($('meta[name="page"]').attr("content")){case"user-account":new r}}}])&&h(e.prototype,i),a&&h(e,a),t}())},urRO:function(t,e,i){t.exports=function(){"use strict";function t(e){return(t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(e)}function e(t,e){for(var i=0;i<e.length;i++){var a=e[i];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}function i(t){return function(t){if(Array.isArray(t)){for(var e=0,i=new Array(t.length);e<t.length;e++)i[e]=t[e];return i}}(t)||function(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}var a="undefined"!=typeof window&&void 0!==window.document,n=a?window:{},o=!!a&&"ontouchstart"in n.document.documentElement,r=!!a&&"PointerEvent"in n,h="".concat("cropper","-crop"),s="".concat("cropper","-disabled"),c="".concat("cropper","-hidden"),l="".concat("cropper","-hide"),p="".concat("cropper","-invisible"),d="".concat("cropper","-modal"),u="".concat("cropper","-move"),m="".concat("cropper","Action"),g="".concat("cropper","Preview"),f=o?"touchstart":"mousedown",v=o?"touchmove":"mousemove",w=o?"touchend touchcancel":"mouseup",b=r?"pointerdown":f,y=r?"pointermove":v,x=r?"pointerup pointercancel":w,M=/^e|w|s|n|se|sw|ne|nw|all|crop|move|zoom$/,C=/^data:/,D=/^data:image\/jpeg;base64,/,k=/^img|canvas$/i,B={viewMode:0,dragMode:"crop",initialAspectRatio:NaN,aspectRatio:NaN,data:null,preview:"",responsive:!0,restore:!0,checkCrossOrigin:!0,checkOrientation:!0,modal:!0,guides:!0,center:!0,highlight:!0,background:!0,autoCrop:!0,autoCropArea:.8,movable:!0,rotatable:!0,scalable:!0,zoomable:!0,zoomOnTouch:!0,zoomOnWheel:!0,wheelZoomRatio:.1,cropBoxMovable:!0,cropBoxResizable:!0,toggleDragModeOnDblclick:!0,minCanvasWidth:0,minCanvasHeight:0,minCropBoxWidth:0,minCropBoxHeight:0,minContainerWidth:200,minContainerHeight:100,ready:null,cropstart:null,cropmove:null,cropend:null,crop:null,zoom:null},T=Number.isNaN||n.isNaN;function E(t){return"number"==typeof t&&!T(t)}var W=function(t){return t>0&&t<1/0};function L(t){return void 0===t}function N(e){return"object"===t(e)&&null!==e}var H=Object.prototype.hasOwnProperty;function z(t){if(!N(t))return!1;try{var e=t.constructor,i=e.prototype;return e&&i&&H.call(i,"isPrototypeOf")}catch(t){return!1}}function O(t){return"function"==typeof t}var R=Array.prototype.slice;function Y(t){return Array.from?Array.from(t):R.call(t)}function X(t,e){return t&&O(e)&&(Array.isArray(t)||E(t.length)?Y(t).forEach(function(i,a){e.call(t,i,a,t)}):N(t)&&Object.keys(t).forEach(function(i){e.call(t,t[i],i,t)})),t}var A=Object.assign||function(t){for(var e=arguments.length,i=new Array(e>1?e-1:0),a=1;a<e;a++)i[a-1]=arguments[a];return N(t)&&i.length>0&&i.forEach(function(e){N(e)&&Object.keys(e).forEach(function(i){t[i]=e[i]})}),t},S=/\.\d*(?:0|9){12}\d*$/;function $(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1e11;return S.test(t)?Math.round(t*e)/e:t}var U=/^width|height|left|top|marginLeft|marginTop$/;function I(t,e){var i=t.style;X(e,function(t,e){U.test(e)&&E(t)&&(t="".concat(t,"px")),i[e]=t})}function j(t,e){if(e)if(E(t.length))X(t,function(t){j(t,e)});else if(t.classList)t.classList.add(e);else{var i=t.className.trim();i?i.indexOf(e)<0&&(t.className="".concat(i," ").concat(e)):t.className=e}}function P(t,e){e&&(E(t.length)?X(t,function(t){P(t,e)}):t.classList?t.classList.remove(e):t.className.indexOf(e)>=0&&(t.className=t.className.replace(e,"")))}function q(t,e,i){e&&(E(t.length)?X(t,function(t){q(t,e,i)}):i?j(t,e):P(t,e))}var Q=/([a-z\d])([A-Z])/g;function F(t){return t.replace(Q,"$1-$2").toLowerCase()}function K(t,e){return N(t[e])?t[e]:t.dataset?t.dataset[e]:t.getAttribute("data-".concat(F(e)))}function V(t,e,i){N(i)?t[e]=i:t.dataset?t.dataset[e]=i:t.setAttribute("data-".concat(F(e)),i)}var Z=/\s\s*/,_=function(){var t=!1;if(a){var e=!1,i=function(){},o=Object.defineProperty({},"once",{get:function(){return t=!0,e},set:function(t){e=t}});n.addEventListener("test",i,o),n.removeEventListener("test",i,o)}return t}();function J(t,e,i){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{},n=i;e.trim().split(Z).forEach(function(e){if(!_){var o=t.listeners;o&&o[e]&&o[e][i]&&(n=o[e][i],delete o[e][i],0===Object.keys(o[e]).length&&delete o[e],0===Object.keys(o).length&&delete t.listeners)}t.removeEventListener(e,n,a)})}function G(t,e,i){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{},n=i;e.trim().split(Z).forEach(function(e){if(a.once&&!_){var o=t.listeners,r=void 0===o?{}:o;n=function(){delete r[e][i],t.removeEventListener(e,n,a);for(var o=arguments.length,h=new Array(o),s=0;s<o;s++)h[s]=arguments[s];i.apply(t,h)},r[e]||(r[e]={}),r[e][i]&&t.removeEventListener(e,r[e][i],a),r[e][i]=n,t.listeners=r}t.addEventListener(e,n,a)})}function tt(t,e,i){var a;return O(Event)&&O(CustomEvent)?a=new CustomEvent(e,{detail:i,bubbles:!0,cancelable:!0}):(a=document.createEvent("CustomEvent")).initCustomEvent(e,!0,!0,i),t.dispatchEvent(a)}function et(t){var e=t.getBoundingClientRect();return{left:e.left+(window.pageXOffset-document.documentElement.clientLeft),top:e.top+(window.pageYOffset-document.documentElement.clientTop)}}var it=n.location,at=/^(\w+:)\/\/([^:/?#]*):?(\d*)/i;function nt(t){var e=t.match(at);return null!==e&&(e[1]!==it.protocol||e[2]!==it.hostname||e[3]!==it.port)}function ot(t){var e="timestamp=".concat((new Date).getTime());return t+(-1===t.indexOf("?")?"?":"&")+e}function rt(t){var e=t.rotate,i=t.scaleX,a=t.scaleY,n=t.translateX,o=t.translateY,r=[];E(n)&&0!==n&&r.push("translateX(".concat(n,"px)")),E(o)&&0!==o&&r.push("translateY(".concat(o,"px)")),E(e)&&0!==e&&r.push("rotate(".concat(e,"deg)")),E(i)&&1!==i&&r.push("scaleX(".concat(i,")")),E(a)&&1!==a&&r.push("scaleY(".concat(a,")"));var h=r.length?r.join(" "):"none";return{WebkitTransform:h,msTransform:h,transform:h}}function ht(t,e){var i=t.pageX,a=t.pageY,n={endX:i,endY:a};return e?n:A({startX:i,startY:a},n)}function st(t){var e=t.aspectRatio,i=t.height,a=t.width,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"contain",o=W(a),r=W(i);if(o&&r){var h=i*e;"contain"===n&&h>a||"cover"===n&&h<a?i=a/e:a=i*e}else o?i=a/e:r&&(a=i*e);return{width:a,height:i}}function ct(t,e,a,n){var o=e.aspectRatio,r=e.naturalWidth,h=e.naturalHeight,s=e.rotate,c=void 0===s?0:s,l=e.scaleX,p=void 0===l?1:l,d=e.scaleY,u=void 0===d?1:d,m=a.aspectRatio,g=a.naturalWidth,f=a.naturalHeight,v=n.fillColor,w=void 0===v?"transparent":v,b=n.imageSmoothingEnabled,y=void 0===b||b,x=n.imageSmoothingQuality,M=void 0===x?"low":x,C=n.maxWidth,D=void 0===C?1/0:C,k=n.maxHeight,B=void 0===k?1/0:k,T=n.minWidth,E=void 0===T?0:T,W=n.minHeight,L=void 0===W?0:W,N=document.createElement("canvas"),H=N.getContext("2d"),z=st({aspectRatio:m,width:D,height:B}),O=st({aspectRatio:m,width:E,height:L},"cover"),R=Math.min(z.width,Math.max(O.width,g)),Y=Math.min(z.height,Math.max(O.height,f)),X=st({aspectRatio:o,width:D,height:B}),A=st({aspectRatio:o,width:E,height:L},"cover"),S=Math.min(X.width,Math.max(A.width,r)),U=Math.min(X.height,Math.max(A.height,h)),I=[-S/2,-U/2,S,U];return N.width=$(R),N.height=$(Y),H.fillStyle=w,H.fillRect(0,0,R,Y),H.save(),H.translate(R/2,Y/2),H.rotate(c*Math.PI/180),H.scale(p,u),H.imageSmoothingEnabled=y,H.imageSmoothingQuality=M,H.drawImage.apply(H,[t].concat(i(I.map(function(t){return Math.floor($(t))})))),H.restore(),N}var lt=String.fromCharCode,pt=/^data:.*,/;function dt(t){var e,i=new DataView(t);try{var a,n,o;if(255===i.getUint8(0)&&216===i.getUint8(1))for(var r=i.byteLength,h=2;h+1<r;){if(255===i.getUint8(h)&&225===i.getUint8(h+1)){n=h;break}h+=1}if(n){var s=n+10;if("Exif"===function(t,e,i){var a="";i+=e;for(var n=e;n<i;n+=1)a+=lt(t.getUint8(n));return a}(i,n+4,4)){var c=i.getUint16(s);if(((a=18761===c)||19789===c)&&42===i.getUint16(s+2,a)){var l=i.getUint32(s+4,a);l>=8&&(o=s+l)}}}if(o){var p,d,u=i.getUint16(o,a);for(d=0;d<u;d+=1)if(p=o+12*d+2,274===i.getUint16(p,a)){p+=8,e=i.getUint16(p,a),i.setUint16(p,1,a);break}}}catch(t){e=1}return e}var ut={render:function(){this.initContainer(),this.initCanvas(),this.initCropBox(),this.renderCanvas(),this.cropped&&this.renderCropBox()},initContainer:function(){var t=this.element,e=this.options,i=this.container,a=this.cropper;j(a,c),P(t,c);var n={width:Math.max(i.offsetWidth,Number(e.minContainerWidth)||200),height:Math.max(i.offsetHeight,Number(e.minContainerHeight)||100)};this.containerData=n,I(a,{width:n.width,height:n.height}),j(t,c),P(a,c)},initCanvas:function(){var t=this.containerData,e=this.imageData,i=this.options.viewMode,a=Math.abs(e.rotate)%180==90,n=a?e.naturalHeight:e.naturalWidth,o=a?e.naturalWidth:e.naturalHeight,r=n/o,h=t.width,s=t.height;t.height*r>t.width?3===i?h=t.height*r:s=t.width/r:3===i?s=t.width/r:h=t.height*r;var c={aspectRatio:r,naturalWidth:n,naturalHeight:o,width:h,height:s};c.left=(t.width-h)/2,c.top=(t.height-s)/2,c.oldLeft=c.left,c.oldTop=c.top,this.canvasData=c,this.limited=1===i||2===i,this.limitCanvas(!0,!0),this.initialImageData=A({},e),this.initialCanvasData=A({},c)},limitCanvas:function(t,e){var i=this.options,a=this.containerData,n=this.canvasData,o=this.cropBoxData,r=i.viewMode,h=n.aspectRatio,s=this.cropped&&o;if(t){var c=Number(i.minCanvasWidth)||0,l=Number(i.minCanvasHeight)||0;r>1?(c=Math.max(c,a.width),l=Math.max(l,a.height),3===r&&(l*h>c?c=l*h:l=c/h)):r>0&&(c?c=Math.max(c,s?o.width:0):l?l=Math.max(l,s?o.height:0):s&&(c=o.width,(l=o.height)*h>c?c=l*h:l=c/h));var p=st({aspectRatio:h,width:c,height:l});c=p.width,l=p.height,n.minWidth=c,n.minHeight=l,n.maxWidth=1/0,n.maxHeight=1/0}if(e)if(r>(s?0:1)){var d=a.width-n.width,u=a.height-n.height;n.minLeft=Math.min(0,d),n.minTop=Math.min(0,u),n.maxLeft=Math.max(0,d),n.maxTop=Math.max(0,u),s&&this.limited&&(n.minLeft=Math.min(o.left,o.left+(o.width-n.width)),n.minTop=Math.min(o.top,o.top+(o.height-n.height)),n.maxLeft=o.left,n.maxTop=o.top,2===r&&(n.width>=a.width&&(n.minLeft=Math.min(0,d),n.maxLeft=Math.max(0,d)),n.height>=a.height&&(n.minTop=Math.min(0,u),n.maxTop=Math.max(0,u))))}else n.minLeft=-n.width,n.minTop=-n.height,n.maxLeft=a.width,n.maxTop=a.height},renderCanvas:function(t,e){var i=this.canvasData,a=this.imageData;if(e){var n=function(t){var e=t.width,i=t.height,a=t.degree;if(90==(a=Math.abs(a)%180))return{width:i,height:e};var n=a%90*Math.PI/180,o=Math.sin(n),r=Math.cos(n),h=e*r+i*o,s=e*o+i*r;return a>90?{width:s,height:h}:{width:h,height:s}}({width:a.naturalWidth*Math.abs(a.scaleX||1),height:a.naturalHeight*Math.abs(a.scaleY||1),degree:a.rotate||0}),o=n.width,r=n.height,h=i.width*(o/i.naturalWidth),s=i.height*(r/i.naturalHeight);i.left-=(h-i.width)/2,i.top-=(s-i.height)/2,i.width=h,i.height=s,i.aspectRatio=o/r,i.naturalWidth=o,i.naturalHeight=r,this.limitCanvas(!0,!1)}(i.width>i.maxWidth||i.width<i.minWidth)&&(i.left=i.oldLeft),(i.height>i.maxHeight||i.height<i.minHeight)&&(i.top=i.oldTop),i.width=Math.min(Math.max(i.width,i.minWidth),i.maxWidth),i.height=Math.min(Math.max(i.height,i.minHeight),i.maxHeight),this.limitCanvas(!1,!0),i.left=Math.min(Math.max(i.left,i.minLeft),i.maxLeft),i.top=Math.min(Math.max(i.top,i.minTop),i.maxTop),i.oldLeft=i.left,i.oldTop=i.top,I(this.canvas,A({width:i.width,height:i.height},rt({translateX:i.left,translateY:i.top}))),this.renderImage(t),this.cropped&&this.limited&&this.limitCropBox(!0,!0)},renderImage:function(t){var e=this.canvasData,i=this.imageData,a=i.naturalWidth*(e.width/e.naturalWidth),n=i.naturalHeight*(e.height/e.naturalHeight);A(i,{width:a,height:n,left:(e.width-a)/2,top:(e.height-n)/2}),I(this.image,A({width:i.width,height:i.height},rt(A({translateX:i.left,translateY:i.top},i)))),t&&this.output()},initCropBox:function(){var t=this.options,e=this.canvasData,i=t.aspectRatio||t.initialAspectRatio,a=Number(t.autoCropArea)||.8,n={width:e.width,height:e.height};i&&(e.height*i>e.width?n.height=n.width/i:n.width=n.height*i),this.cropBoxData=n,this.limitCropBox(!0,!0),n.width=Math.min(Math.max(n.width,n.minWidth),n.maxWidth),n.height=Math.min(Math.max(n.height,n.minHeight),n.maxHeight),n.width=Math.max(n.minWidth,n.width*a),n.height=Math.max(n.minHeight,n.height*a),n.left=e.left+(e.width-n.width)/2,n.top=e.top+(e.height-n.height)/2,n.oldLeft=n.left,n.oldTop=n.top,this.initialCropBoxData=A({},n)},limitCropBox:function(t,e){var i=this.options,a=this.containerData,n=this.canvasData,o=this.cropBoxData,r=this.limited,h=i.aspectRatio;if(t){var s=Number(i.minCropBoxWidth)||0,c=Number(i.minCropBoxHeight)||0,l=r?Math.min(a.width,n.width,n.width+n.left,a.width-n.left):a.width,p=r?Math.min(a.height,n.height,n.height+n.top,a.height-n.top):a.height;s=Math.min(s,a.width),c=Math.min(c,a.height),h&&(s&&c?c*h>s?c=s/h:s=c*h:s?c=s/h:c&&(s=c*h),p*h>l?p=l/h:l=p*h),o.minWidth=Math.min(s,l),o.minHeight=Math.min(c,p),o.maxWidth=l,o.maxHeight=p}e&&(r?(o.minLeft=Math.max(0,n.left),o.minTop=Math.max(0,n.top),o.maxLeft=Math.min(a.width,n.left+n.width)-o.width,o.maxTop=Math.min(a.height,n.top+n.height)-o.height):(o.minLeft=0,o.minTop=0,o.maxLeft=a.width-o.width,o.maxTop=a.height-o.height))},renderCropBox:function(){var t=this.options,e=this.containerData,i=this.cropBoxData;(i.width>i.maxWidth||i.width<i.minWidth)&&(i.left=i.oldLeft),(i.height>i.maxHeight||i.height<i.minHeight)&&(i.top=i.oldTop),i.width=Math.min(Math.max(i.width,i.minWidth),i.maxWidth),i.height=Math.min(Math.max(i.height,i.minHeight),i.maxHeight),this.limitCropBox(!1,!0),i.left=Math.min(Math.max(i.left,i.minLeft),i.maxLeft),i.top=Math.min(Math.max(i.top,i.minTop),i.maxTop),i.oldLeft=i.left,i.oldTop=i.top,t.movable&&t.cropBoxMovable&&V(this.face,m,i.width>=e.width&&i.height>=e.height?"move":"all"),I(this.cropBox,A({width:i.width,height:i.height},rt({translateX:i.left,translateY:i.top}))),this.cropped&&this.limited&&this.limitCanvas(!0,!0),this.disabled||this.output()},output:function(){this.preview(),tt(this.element,"crop",this.getData())}},mt={initPreview:function(){var t=this.element,e=this.crossOrigin,i=this.options.preview,a=e?this.crossOriginUrl:this.url,n=t.alt||"The image to preview",o=document.createElement("img");if(e&&(o.crossOrigin=e),o.src=a,o.alt=n,this.viewBox.appendChild(o),this.viewBoxImage=o,i){var r=i;"string"==typeof i?r=t.ownerDocument.querySelectorAll(i):i.querySelector&&(r=[i]),this.previews=r,X(r,function(t){var i=document.createElement("img");V(t,g,{width:t.offsetWidth,height:t.offsetHeight,html:t.innerHTML}),e&&(i.crossOrigin=e),i.src=a,i.alt=n,i.style.cssText='display:block;width:100%;height:auto;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation:0deg!important;"',t.innerHTML="",t.appendChild(i)})}},resetPreview:function(){X(this.previews,function(t){var e=K(t,g);I(t,{width:e.width,height:e.height}),t.innerHTML=e.html,function(t,e){if(N(t[e]))try{delete t[e]}catch(i){t[e]=void 0}else if(t.dataset)try{delete t.dataset[e]}catch(i){t.dataset[e]=void 0}else t.removeAttribute("data-".concat(F(e)))}(t,g)})},preview:function(){var t=this.imageData,e=this.canvasData,i=this.cropBoxData,a=i.width,n=i.height,o=t.width,r=t.height,h=i.left-e.left-t.left,s=i.top-e.top-t.top;this.cropped&&!this.disabled&&(I(this.viewBoxImage,A({width:o,height:r},rt(A({translateX:-h,translateY:-s},t)))),X(this.previews,function(e){var i=K(e,g),c=i.width,l=i.height,p=c,d=l,u=1;a&&(d=n*(u=c/a)),n&&d>l&&(p=a*(u=l/n),d=l),I(e,{width:p,height:d}),I(e.getElementsByTagName("img")[0],A({width:o*u,height:r*u},rt(A({translateX:-h*u,translateY:-s*u},t))))}))}},gt={bind:function(){var t=this.element,e=this.options,i=this.cropper;O(e.cropstart)&&G(t,"cropstart",e.cropstart),O(e.cropmove)&&G(t,"cropmove",e.cropmove),O(e.cropend)&&G(t,"cropend",e.cropend),O(e.crop)&&G(t,"crop",e.crop),O(e.zoom)&&G(t,"zoom",e.zoom),G(i,b,this.onCropStart=this.cropStart.bind(this)),e.zoomable&&e.zoomOnWheel&&G(i,"wheel",this.onWheel=this.wheel.bind(this),{passive:!1,capture:!0}),e.toggleDragModeOnDblclick&&G(i,"dblclick",this.onDblclick=this.dblclick.bind(this)),G(t.ownerDocument,y,this.onCropMove=this.cropMove.bind(this)),G(t.ownerDocument,x,this.onCropEnd=this.cropEnd.bind(this)),e.responsive&&G(window,"resize",this.onResize=this.resize.bind(this))},unbind:function(){var t=this.element,e=this.options,i=this.cropper;O(e.cropstart)&&J(t,"cropstart",e.cropstart),O(e.cropmove)&&J(t,"cropmove",e.cropmove),O(e.cropend)&&J(t,"cropend",e.cropend),O(e.crop)&&J(t,"crop",e.crop),O(e.zoom)&&J(t,"zoom",e.zoom),J(i,b,this.onCropStart),e.zoomable&&e.zoomOnWheel&&J(i,"wheel",this.onWheel,{passive:!1,capture:!0}),e.toggleDragModeOnDblclick&&J(i,"dblclick",this.onDblclick),J(t.ownerDocument,y,this.onCropMove),J(t.ownerDocument,x,this.onCropEnd),e.responsive&&J(window,"resize",this.onResize)}},ft={resize:function(){var t=this.options,e=this.container,i=this.containerData,a=Number(t.minContainerWidth)||200,n=Number(t.minContainerHeight)||100;if(!(this.disabled||i.width<=a||i.height<=n)){var o,r,h=e.offsetWidth/i.width;1===h&&e.offsetHeight===i.height||(t.restore&&(o=this.getCanvasData(),r=this.getCropBoxData()),this.render(),t.restore&&(this.setCanvasData(X(o,function(t,e){o[e]=t*h})),this.setCropBoxData(X(r,function(t,e){r[e]=t*h}))))}},dblclick:function(){var t,e;this.disabled||"none"===this.options.dragMode||this.setDragMode((t=this.dragBox,e=h,(t.classList?t.classList.contains(e):t.className.indexOf(e)>-1)?"move":"crop"))},wheel:function(t){var e=this,i=Number(this.options.wheelZoomRatio)||.1,a=1;this.disabled||(t.preventDefault(),this.wheeling||(this.wheeling=!0,setTimeout(function(){e.wheeling=!1},50),t.deltaY?a=t.deltaY>0?1:-1:t.wheelDelta?a=-t.wheelDelta/120:t.detail&&(a=t.detail>0?1:-1),this.zoom(-a*i,t)))},cropStart:function(t){var e=t.buttons,i=t.button;if(!(this.disabled||E(e)&&1!==e||E(i)&&0!==i||t.ctrlKey)){var a,n=this.options,o=this.pointers;t.changedTouches?X(t.changedTouches,function(t){o[t.identifier]=ht(t)}):o[t.pointerId||0]=ht(t),a=Object.keys(o).length>1&&n.zoomable&&n.zoomOnTouch?"zoom":K(t.target,m),M.test(a)&&!1!==tt(this.element,"cropstart",{originalEvent:t,action:a})&&(t.preventDefault(),this.action=a,this.cropping=!1,"crop"===a&&(this.cropping=!0,j(this.dragBox,d)))}},cropMove:function(t){var e=this.action;if(!this.disabled&&e){var i=this.pointers;t.preventDefault(),!1!==tt(this.element,"cropmove",{originalEvent:t,action:e})&&(t.changedTouches?X(t.changedTouches,function(t){A(i[t.identifier]||{},ht(t,!0))}):A(i[t.pointerId||0]||{},ht(t,!0)),this.change(t))}},cropEnd:function(t){if(!this.disabled){var e=this.action,i=this.pointers;t.changedTouches?X(t.changedTouches,function(t){delete i[t.identifier]}):delete i[t.pointerId||0],e&&(t.preventDefault(),Object.keys(i).length||(this.action=""),this.cropping&&(this.cropping=!1,q(this.dragBox,d,this.cropped&&this.options.modal)),tt(this.element,"cropend",{originalEvent:t,action:e}))}}},vt={change:function(t){var e,i=this.options,a=this.canvasData,n=this.containerData,o=this.cropBoxData,r=this.pointers,h=this.action,s=i.aspectRatio,l=o.left,p=o.top,d=o.width,u=o.height,m=l+d,g=p+u,f=0,v=0,w=n.width,b=n.height,y=!0;!s&&t.shiftKey&&(s=d&&u?d/u:1),this.limited&&(f=o.minLeft,v=o.minTop,w=f+Math.min(n.width,a.width,a.left+a.width),b=v+Math.min(n.height,a.height,a.top+a.height));var x=r[Object.keys(r)[0]],M={x:x.endX-x.startX,y:x.endY-x.startY},C=function(t){switch(t){case"e":m+M.x>w&&(M.x=w-m);break;case"w":l+M.x<f&&(M.x=f-l);break;case"n":p+M.y<v&&(M.y=v-p);break;case"s":g+M.y>b&&(M.y=b-g)}};switch(h){case"all":l+=M.x,p+=M.y;break;case"e":if(M.x>=0&&(m>=w||s&&(p<=v||g>=b))){y=!1;break}C("e"),(d+=M.x)<0&&(h="w",l-=d=-d),s&&(u=d/s,p+=(o.height-u)/2);break;case"n":if(M.y<=0&&(p<=v||s&&(l<=f||m>=w))){y=!1;break}C("n"),u-=M.y,p+=M.y,u<0&&(h="s",p-=u=-u),s&&(d=u*s,l+=(o.width-d)/2);break;case"w":if(M.x<=0&&(l<=f||s&&(p<=v||g>=b))){y=!1;break}C("w"),d-=M.x,l+=M.x,d<0&&(h="e",l-=d=-d),s&&(u=d/s,p+=(o.height-u)/2);break;case"s":if(M.y>=0&&(g>=b||s&&(l<=f||m>=w))){y=!1;break}C("s"),(u+=M.y)<0&&(h="n",p-=u=-u),s&&(d=u*s,l+=(o.width-d)/2);break;case"ne":if(s){if(M.y<=0&&(p<=v||m>=w)){y=!1;break}C("n"),u-=M.y,p+=M.y,d=u*s}else C("n"),C("e"),M.x>=0?m<w?d+=M.x:M.y<=0&&p<=v&&(y=!1):d+=M.x,M.y<=0?p>v&&(u-=M.y,p+=M.y):(u-=M.y,p+=M.y);d<0&&u<0?(h="sw",p-=u=-u,l-=d=-d):d<0?(h="nw",l-=d=-d):u<0&&(h="se",p-=u=-u);break;case"nw":if(s){if(M.y<=0&&(p<=v||l<=f)){y=!1;break}C("n"),u-=M.y,p+=M.y,d=u*s,l+=o.width-d}else C("n"),C("w"),M.x<=0?l>f?(d-=M.x,l+=M.x):M.y<=0&&p<=v&&(y=!1):(d-=M.x,l+=M.x),M.y<=0?p>v&&(u-=M.y,p+=M.y):(u-=M.y,p+=M.y);d<0&&u<0?(h="se",p-=u=-u,l-=d=-d):d<0?(h="ne",l-=d=-d):u<0&&(h="sw",p-=u=-u);break;case"sw":if(s){if(M.x<=0&&(l<=f||g>=b)){y=!1;break}C("w"),d-=M.x,l+=M.x,u=d/s}else C("s"),C("w"),M.x<=0?l>f?(d-=M.x,l+=M.x):M.y>=0&&g>=b&&(y=!1):(d-=M.x,l+=M.x),M.y>=0?g<b&&(u+=M.y):u+=M.y;d<0&&u<0?(h="ne",p-=u=-u,l-=d=-d):d<0?(h="se",l-=d=-d):u<0&&(h="nw",p-=u=-u);break;case"se":if(s){if(M.x>=0&&(m>=w||g>=b)){y=!1;break}C("e"),u=(d+=M.x)/s}else C("s"),C("e"),M.x>=0?m<w?d+=M.x:M.y>=0&&g>=b&&(y=!1):d+=M.x,M.y>=0?g<b&&(u+=M.y):u+=M.y;d<0&&u<0?(h="nw",p-=u=-u,l-=d=-d):d<0?(h="sw",l-=d=-d):u<0&&(h="ne",p-=u=-u);break;case"move":this.move(M.x,M.y),y=!1;break;case"zoom":this.zoom(function(t){var e=A({},t),i=[];return X(t,function(t,a){delete e[a],X(e,function(e){var a=Math.abs(t.startX-e.startX),n=Math.abs(t.startY-e.startY),o=Math.abs(t.endX-e.endX),r=Math.abs(t.endY-e.endY),h=Math.sqrt(a*a+n*n),s=(Math.sqrt(o*o+r*r)-h)/h;i.push(s)})}),i.sort(function(t,e){return Math.abs(t)<Math.abs(e)}),i[0]}(r),t),y=!1;break;case"crop":if(!M.x||!M.y){y=!1;break}e=et(this.cropper),l=x.startX-e.left,p=x.startY-e.top,d=o.minWidth,u=o.minHeight,M.x>0?h=M.y>0?"se":"ne":M.x<0&&(l-=d,h=M.y>0?"sw":"nw"),M.y<0&&(p-=u),this.cropped||(P(this.cropBox,c),this.cropped=!0,this.limited&&this.limitCropBox(!0,!0))}y&&(o.width=d,o.height=u,o.left=l,o.top=p,this.action=h,this.renderCropBox()),X(r,function(t){t.startX=t.endX,t.startY=t.endY})}},wt={crop:function(){return!this.ready||this.cropped||this.disabled||(this.cropped=!0,this.limitCropBox(!0,!0),this.options.modal&&j(this.dragBox,d),P(this.cropBox,c),this.setCropBoxData(this.initialCropBoxData)),this},reset:function(){return this.ready&&!this.disabled&&(this.imageData=A({},this.initialImageData),this.canvasData=A({},this.initialCanvasData),this.cropBoxData=A({},this.initialCropBoxData),this.renderCanvas(),this.cropped&&this.renderCropBox()),this},clear:function(){return this.cropped&&!this.disabled&&(A(this.cropBoxData,{left:0,top:0,width:0,height:0}),this.cropped=!1,this.renderCropBox(),this.limitCanvas(!0,!0),this.renderCanvas(),P(this.dragBox,d),j(this.cropBox,c)),this},replace:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return!this.disabled&&t&&(this.isImg&&(this.element.src=t),e?(this.url=t,this.image.src=t,this.ready&&(this.viewBoxImage.src=t,X(this.previews,function(e){e.getElementsByTagName("img")[0].src=t}))):(this.isImg&&(this.replaced=!0),this.options.data=null,this.uncreate(),this.load(t))),this},enable:function(){return this.ready&&this.disabled&&(this.disabled=!1,P(this.cropper,s)),this},disable:function(){return this.ready&&!this.disabled&&(this.disabled=!0,j(this.cropper,s)),this},destroy:function(){var t=this.element;return t.cropper?(t.cropper=void 0,this.isImg&&this.replaced&&(t.src=this.originalUrl),this.uncreate(),this):this},move:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:t,i=this.canvasData,a=i.left,n=i.top;return this.moveTo(L(t)?t:a+Number(t),L(e)?e:n+Number(e))},moveTo:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:t,i=this.canvasData,a=!1;return t=Number(t),e=Number(e),this.ready&&!this.disabled&&this.options.movable&&(E(t)&&(i.left=t,a=!0),E(e)&&(i.top=e,a=!0),a&&this.renderCanvas(!0)),this},zoom:function(t,e){var i=this.canvasData;return t=(t=Number(t))<0?1/(1-t):1+t,this.zoomTo(i.width*t/i.naturalWidth,null,e)},zoomTo:function(t,e,i){var a=this.options,n=this.canvasData,o=n.width,r=n.height,h=n.naturalWidth,s=n.naturalHeight;if((t=Number(t))>=0&&this.ready&&!this.disabled&&a.zoomable){var c=h*t,l=s*t;if(!1===tt(this.element,"zoom",{ratio:t,oldRatio:o/h,originalEvent:i}))return this;if(i){var p=this.pointers,d=et(this.cropper),u=p&&Object.keys(p).length?function(t){var e=0,i=0,a=0;return X(t,function(t){var n=t.startX,o=t.startY;e+=n,i+=o,a+=1}),{pageX:e/=a,pageY:i/=a}}(p):{pageX:i.pageX,pageY:i.pageY};n.left-=(c-o)*((u.pageX-d.left-n.left)/o),n.top-=(l-r)*((u.pageY-d.top-n.top)/r)}else z(e)&&E(e.x)&&E(e.y)?(n.left-=(c-o)*((e.x-n.left)/o),n.top-=(l-r)*((e.y-n.top)/r)):(n.left-=(c-o)/2,n.top-=(l-r)/2);n.width=c,n.height=l,this.renderCanvas(!0)}return this},rotate:function(t){return this.rotateTo((this.imageData.rotate||0)+Number(t))},rotateTo:function(t){return E(t=Number(t))&&this.ready&&!this.disabled&&this.options.rotatable&&(this.imageData.rotate=t%360,this.renderCanvas(!0,!0)),this},scaleX:function(t){var e=this.imageData.scaleY;return this.scale(t,E(e)?e:1)},scaleY:function(t){var e=this.imageData.scaleX;return this.scale(E(e)?e:1,t)},scale:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:t,i=this.imageData,a=!1;return t=Number(t),e=Number(e),this.ready&&!this.disabled&&this.options.scalable&&(E(t)&&(i.scaleX=t,a=!0),E(e)&&(i.scaleY=e,a=!0),a&&this.renderCanvas(!0,!0)),this},getData:function(){var t,e=arguments.length>0&&void 0!==arguments[0]&&arguments[0],i=this.options,a=this.imageData,n=this.canvasData,o=this.cropBoxData;if(this.ready&&this.cropped){t={x:o.left-n.left,y:o.top-n.top,width:o.width,height:o.height};var r=a.width/a.naturalWidth;if(X(t,function(e,i){t[i]=e/r}),e){var h=Math.round(t.y+t.height),s=Math.round(t.x+t.width);t.x=Math.round(t.x),t.y=Math.round(t.y),t.width=s-t.x,t.height=h-t.y}}else t={x:0,y:0,width:0,height:0};return i.rotatable&&(t.rotate=a.rotate||0),i.scalable&&(t.scaleX=a.scaleX||1,t.scaleY=a.scaleY||1),t},setData:function(t){var e=this.options,i=this.imageData,a=this.canvasData,n={};if(this.ready&&!this.disabled&&z(t)){var o=!1;e.rotatable&&E(t.rotate)&&t.rotate!==i.rotate&&(i.rotate=t.rotate,o=!0),e.scalable&&(E(t.scaleX)&&t.scaleX!==i.scaleX&&(i.scaleX=t.scaleX,o=!0),E(t.scaleY)&&t.scaleY!==i.scaleY&&(i.scaleY=t.scaleY,o=!0)),o&&this.renderCanvas(!0,!0);var r=i.width/i.naturalWidth;E(t.x)&&(n.left=t.x*r+a.left),E(t.y)&&(n.top=t.y*r+a.top),E(t.width)&&(n.width=t.width*r),E(t.height)&&(n.height=t.height*r),this.setCropBoxData(n)}return this},getContainerData:function(){return this.ready?A({},this.containerData):{}},getImageData:function(){return this.sized?A({},this.imageData):{}},getCanvasData:function(){var t=this.canvasData,e={};return this.ready&&X(["left","top","width","height","naturalWidth","naturalHeight"],function(i){e[i]=t[i]}),e},setCanvasData:function(t){var e=this.canvasData,i=e.aspectRatio;return this.ready&&!this.disabled&&z(t)&&(E(t.left)&&(e.left=t.left),E(t.top)&&(e.top=t.top),E(t.width)?(e.width=t.width,e.height=t.width/i):E(t.height)&&(e.height=t.height,e.width=t.height*i),this.renderCanvas(!0)),this},getCropBoxData:function(){var t,e=this.cropBoxData;return this.ready&&this.cropped&&(t={left:e.left,top:e.top,width:e.width,height:e.height}),t||{}},setCropBoxData:function(t){var e,i,a=this.cropBoxData,n=this.options.aspectRatio;return this.ready&&this.cropped&&!this.disabled&&z(t)&&(E(t.left)&&(a.left=t.left),E(t.top)&&(a.top=t.top),E(t.width)&&t.width!==a.width&&(e=!0,a.width=t.width),E(t.height)&&t.height!==a.height&&(i=!0,a.height=t.height),n&&(e?a.height=a.width/n:i&&(a.width=a.height*n)),this.renderCropBox()),this},getCroppedCanvas:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};if(!this.ready||!window.HTMLCanvasElement)return null;var e=this.canvasData,a=ct(this.image,this.imageData,e,t);if(!this.cropped)return a;var n=this.getData(),o=n.x,r=n.y,h=n.width,s=n.height,c=a.width/Math.floor(e.naturalWidth);1!==c&&(o*=c,r*=c,h*=c,s*=c);var l=h/s,p=st({aspectRatio:l,width:t.maxWidth||1/0,height:t.maxHeight||1/0}),d=st({aspectRatio:l,width:t.minWidth||0,height:t.minHeight||0},"cover"),u=st({aspectRatio:l,width:t.width||(1!==c?a.width:h),height:t.height||(1!==c?a.height:s)}),m=u.width,g=u.height;m=Math.min(p.width,Math.max(d.width,m)),g=Math.min(p.height,Math.max(d.height,g));var f=document.createElement("canvas"),v=f.getContext("2d");f.width=$(m),f.height=$(g),v.fillStyle=t.fillColor||"transparent",v.fillRect(0,0,m,g);var w=t.imageSmoothingEnabled,b=void 0===w||w,y=t.imageSmoothingQuality;v.imageSmoothingEnabled=b,y&&(v.imageSmoothingQuality=y);var x,M,C,D,k,B,T=a.width,E=a.height,W=o,L=r;W<=-h||W>T?(W=0,x=0,C=0,k=0):W<=0?(C=-W,W=0,k=x=Math.min(T,h+W)):W<=T&&(C=0,k=x=Math.min(h,T-W)),x<=0||L<=-s||L>E?(L=0,M=0,D=0,B=0):L<=0?(D=-L,L=0,B=M=Math.min(E,s+L)):L<=E&&(D=0,B=M=Math.min(s,E-L));var N=[W,L,x,M];if(k>0&&B>0){var H=m/h;N.push(C*H,D*H,k*H,B*H)}return v.drawImage.apply(v,[a].concat(i(N.map(function(t){return Math.floor($(t))})))),f},setAspectRatio:function(t){var e=this.options;return this.disabled||L(t)||(e.aspectRatio=Math.max(0,t)||NaN,this.ready&&(this.initCropBox(),this.cropped&&this.renderCropBox())),this},setDragMode:function(t){var e=this.options,i=this.dragBox,a=this.face;if(this.ready&&!this.disabled){var n="crop"===t,o=e.movable&&"move"===t;t=n||o?t:"none",e.dragMode=t,V(i,m,t),q(i,h,n),q(i,u,o),e.cropBoxMovable||(V(a,m,t),q(a,h,n),q(a,u,o))}return this}},bt=n.Cropper,yt=function(){function t(e){var i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};if(function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),!e||!k.test(e.tagName))throw new Error("The first argument is required and must be an <img> or <canvas> element.");this.element=e,this.options=A({},B,z(i)&&i),this.cropped=!1,this.disabled=!1,this.pointers={},this.ready=!1,this.reloading=!1,this.replaced=!1,this.sized=!1,this.sizing=!1,this.init()}var i,a,o;return i=t,o=[{key:"noConflict",value:function(){return window.Cropper=bt,t}},{key:"setDefaults",value:function(t){A(B,z(t)&&t)}}],(a=[{key:"init",value:function(){var t,e=this.element,i=e.tagName.toLowerCase();if(!e.cropper){if(e.cropper=this,"img"===i){if(this.isImg=!0,t=e.getAttribute("src")||"",this.originalUrl=t,!t)return;t=e.src}else"canvas"===i&&window.HTMLCanvasElement&&(t=e.toDataURL());this.load(t)}}},{key:"load",value:function(t){var e=this;if(t){this.url=t,this.imageData={};var i=this.element,a=this.options;if(a.rotatable||a.scalable||(a.checkOrientation=!1),a.checkOrientation&&window.ArrayBuffer)if(C.test(t))D.test(t)?this.read((n=t.replace(pt,""),o=atob(n),r=new ArrayBuffer(o.length),X(h=new Uint8Array(r),function(t,e){h[e]=o.charCodeAt(e)}),r)):this.clone();else{var n,o,r,h,s=new XMLHttpRequest,c=this.clone.bind(this);this.reloading=!0,this.xhr=s,s.onabort=c,s.onerror=c,s.ontimeout=c,s.onprogress=function(){"image/jpeg"!==s.getResponseHeader("content-type")&&s.abort()},s.onload=function(){e.read(s.response)},s.onloadend=function(){e.reloading=!1,e.xhr=null},a.checkCrossOrigin&&nt(t)&&i.crossOrigin&&(t=ot(t)),s.open("GET",t),s.responseType="arraybuffer",s.withCredentials="use-credentials"===i.crossOrigin,s.send()}else this.clone()}}},{key:"read",value:function(t){var e=this.options,i=this.imageData,a=dt(t),n=0,o=1,r=1;if(a>1){this.url=function(t,e){for(var i=[],a=new Uint8Array(t);a.length>0;)i.push(lt.apply(null,Y(a.subarray(0,8192)))),a=a.subarray(8192);return"data:".concat(e,";base64,").concat(btoa(i.join("")))}(t,"image/jpeg");var h=function(t){var e=0,i=1,a=1;switch(t){case 2:i=-1;break;case 3:e=-180;break;case 4:a=-1;break;case 5:e=90,a=-1;break;case 6:e=90;break;case 7:e=90,i=-1;break;case 8:e=-90}return{rotate:e,scaleX:i,scaleY:a}}(a);n=h.rotate,o=h.scaleX,r=h.scaleY}e.rotatable&&(i.rotate=n),e.scalable&&(i.scaleX=o,i.scaleY=r),this.clone()}},{key:"clone",value:function(){var t=this.element,e=this.url,i=t.crossOrigin,a=e;this.options.checkCrossOrigin&&nt(e)&&(i||(i="anonymous"),a=ot(e)),this.crossOrigin=i,this.crossOriginUrl=a;var n=document.createElement("img");i&&(n.crossOrigin=i),n.src=a||e,n.alt=t.alt||"The image to crop",this.image=n,n.onload=this.start.bind(this),n.onerror=this.stop.bind(this),j(n,l),t.parentNode.insertBefore(n,t.nextSibling)}},{key:"start",value:function(){var t=this,e=this.image;e.onload=null,e.onerror=null,this.sizing=!0;var i=n.navigator&&/(?:iPad|iPhone|iPod).*?AppleWebKit/i.test(n.navigator.userAgent),a=function(e,i){A(t.imageData,{naturalWidth:e,naturalHeight:i,aspectRatio:e/i}),t.sizing=!1,t.sized=!0,t.build()};if(!e.naturalWidth||i){var o=document.createElement("img"),r=document.body||document.documentElement;this.sizingImage=o,o.onload=function(){a(o.width,o.height),i||r.removeChild(o)},o.src=e.src,i||(o.style.cssText="left:0;max-height:none!important;max-width:none!important;min-height:0!important;min-width:0!important;opacity:0;position:absolute;top:0;z-index:-1;",r.appendChild(o))}else a(e.naturalWidth,e.naturalHeight)}},{key:"stop",value:function(){var t=this.image;t.onload=null,t.onerror=null,t.parentNode.removeChild(t),this.image=null}},{key:"build",value:function(){if(this.sized&&!this.ready){var t=this.element,e=this.options,i=this.image,a=t.parentNode,n=document.createElement("div");n.innerHTML='<div class="cropper-container" touch-action="none"><div class="cropper-wrap-box"><div class="cropper-canvas"></div></div><div class="cropper-drag-box"></div><div class="cropper-crop-box"><span class="cropper-view-box"></span><span class="cropper-dashed dashed-h"></span><span class="cropper-dashed dashed-v"></span><span class="cropper-center"></span><span class="cropper-face"></span><span class="cropper-line line-e" data-cropper-action="e"></span><span class="cropper-line line-n" data-cropper-action="n"></span><span class="cropper-line line-w" data-cropper-action="w"></span><span class="cropper-line line-s" data-cropper-action="s"></span><span class="cropper-point point-e" data-cropper-action="e"></span><span class="cropper-point point-n" data-cropper-action="n"></span><span class="cropper-point point-w" data-cropper-action="w"></span><span class="cropper-point point-s" data-cropper-action="s"></span><span class="cropper-point point-ne" data-cropper-action="ne"></span><span class="cropper-point point-nw" data-cropper-action="nw"></span><span class="cropper-point point-sw" data-cropper-action="sw"></span><span class="cropper-point point-se" data-cropper-action="se"></span></div></div>';var o=n.querySelector(".".concat("cropper","-container")),r=o.querySelector(".".concat("cropper","-canvas")),h=o.querySelector(".".concat("cropper","-drag-box")),s=o.querySelector(".".concat("cropper","-crop-box")),d=s.querySelector(".".concat("cropper","-face"));this.container=a,this.cropper=o,this.canvas=r,this.dragBox=h,this.cropBox=s,this.viewBox=o.querySelector(".".concat("cropper","-view-box")),this.face=d,r.appendChild(i),j(t,c),a.insertBefore(o,t.nextSibling),this.isImg||P(i,l),this.initPreview(),this.bind(),e.initialAspectRatio=Math.max(0,e.initialAspectRatio)||NaN,e.aspectRatio=Math.max(0,e.aspectRatio)||NaN,e.viewMode=Math.max(0,Math.min(3,Math.round(e.viewMode)))||0,j(s,c),e.guides||j(s.getElementsByClassName("".concat("cropper","-dashed")),c),e.center||j(s.getElementsByClassName("".concat("cropper","-center")),c),e.background&&j(o,"".concat("cropper","-bg")),e.highlight||j(d,p),e.cropBoxMovable&&(j(d,u),V(d,m,"all")),e.cropBoxResizable||(j(s.getElementsByClassName("".concat("cropper","-line")),c),j(s.getElementsByClassName("".concat("cropper","-point")),c)),this.render(),this.ready=!0,this.setDragMode(e.dragMode),e.autoCrop&&this.crop(),this.setData(e.data),O(e.ready)&&G(t,"ready",e.ready,{once:!0}),tt(t,"ready")}}},{key:"unbuild",value:function(){this.ready&&(this.ready=!1,this.unbind(),this.resetPreview(),this.cropper.parentNode.removeChild(this.cropper),P(this.element,c))}},{key:"uncreate",value:function(){this.ready?(this.unbuild(),this.ready=!1,this.cropped=!1):this.sizing?(this.sizingImage.onload=null,this.sizing=!1,this.sized=!1):this.reloading?(this.xhr.onabort=null,this.xhr.abort()):this.image&&this.stop()}}])&&e(i.prototype,a),o&&e(i,o),t}();return A(yt.prototype,ut,mt,gt,ft,vt,wt),yt}()}},[[3,0]]]);