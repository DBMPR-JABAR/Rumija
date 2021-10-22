@extends('admin.layout.index')

@section('page-body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center>
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('send.notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Body</label>
                            <textarea class="form-control" name="body"></textarea>
                          </div>
                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js"></script>
<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-messaging.js"></script>

<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional

// $(document).ready(function () {
  var firebaseConfig = {
    apiKey: "AIzaSyBWdniFbHGMogT-BogQSWZl1Q-5U2-CvFY",
    authDomain: "temanjabar-push-notification.firebaseapp.com",
    projectId: "temanjabar-push-notification",
    storageBucket: "temanjabar-push-notification.appspot.com",
    messagingSenderId: "174739302401",
    appId: "1:174739302401:web:2fcf4407203e2e407e3800",
    measurementId: "G-1TVQ08B5BG"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
  const messaging = firebase.messaging();
  function initFirebaseMessagingRegistration() {
    messaging
    .requestPermission()
    .then(function () {
        return messaging.getToken()
    })
    .then(function(token) {
        console.log(token);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("save-token") }}',
            type: 'POST',
            data: {
                token: token
            },
            dataType: 'JSON',
            success: function (response) {
                alert('Token saved successfully.');
            },
            error: function (err) {
                console.log('User Chat Token Error'+ err);
            },
        });
    }).catch(function (err) {
        console.log('User Chat Token Error'+ err);
    });
}
  messaging.onMessage(function(payload) {
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };
    new Notification(noteTitle, noteOptions);

  });
// });
</script>
@endsection
