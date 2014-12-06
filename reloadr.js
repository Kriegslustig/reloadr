window.addEventListener('load', function(){

  Notification.requestPermission(function (status) {
    if (Notification.permission !== status) {
      Notification.permission = status;
    }
  });

  reloadr['reload'] = function () {
    location.reload();
  }
  var source = new EventSource(reloadr.reloadSource);
  source.addEventListener('message', function(e){
    if(e.data.indexOf('reloadr: ') > -1) {
      var method = e.data.split('reloadr: ')[1];
        var n = new Notification('Reloadr', {body: location.href.split('//')[1] + ' has reloaded'});
      reloadr[method]();
    }
  }, false)
}, false);