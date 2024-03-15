importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js')

var firebaseConfig = {
  apiKey: "AIzaSyCAE7RoSAc1C19WIPku6cb6kGLPrZF9bQc",
  authDomain: "bfp-website-b54a3.firebaseapp.com",
  projectId: "bfp-website-b54a3",
  storageBucket: "bfp-website-b54a3.appspot.com",
  messagingSenderId: "592874205081",
  appId: "1:592874205081:web:9cae8e44a8e1ac22f1ed08",
  measurementId: "G-7F4VDV2KPC"
};

firebase.initializeApp(firebaseConfig);
const fcm = firebase.messaging()


fcm.getToken({ vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc' 
}).then((currentToken) => {
        console.log('Token retrieved:', currentToken);
    });

  fcm.onBackgroundMessage((data) => {
    console.log('onBackgroundMessage: ', data)
  })