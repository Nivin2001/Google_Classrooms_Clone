// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import{getMessaging,getToken } from "firebase/messaging";
//import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyCUWVZqmDgFCPicAgJfvFO4JUxticWopEg",
  authDomain: "geeks-classroom-25f07.firebaseapp.com",
  projectId: "geeks-classroom-25f07",
  storageBucket: "geeks-classroom-25f07.appspot.com",
  messagingSenderId: "159299035049",
  appId: "1:159299035049:web:25375d9df7403e1142aeb8",
  measurementId: "G-YDPHT78BK9"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
getToken(messaging,{vapidkey:"BAPbD0KmPLvO8gDvlxg7jbjKkoJhu-CPs7neA4aQCVm8InBN55aBodFNcHUzM_fmEhW238"});