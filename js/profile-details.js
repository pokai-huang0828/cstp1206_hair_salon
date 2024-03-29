document.addEventListener('DOMContentLoaded', async function() {
    
    // get userID from param
    let id = getParamID();

    // get data from database
    fileName = 'http://localhost/inc/utilities/StylistController.php';
    let data = await getData(fileName);

    if(!data){
        displayError();
    } else {
        // find the correct data
        profile = findProfileByID(data, id);

        // display data
        if(!profile){
            displayError();
        } else {
            displayProfile(profile);
        }
    }

});

function getData(fileName){
    return fetch(fileName).then((data)=> data.json()); 
}

function getParamID(){
    let params = new URLSearchParams(document.location.search.substring(1));
    return params.get("id");
}

function findProfileByID(data, id){
    return data.find(obj => obj.userID == id);
}

function displayError(){
    document.getElementsByClassName("card")[0].style.display = "none";
    document.getElementsByClassName("backButton")[0].style.display = "none";
    document.getElementsByClassName("error")[0].style.display = "block";
};

function displayProfile(profile){
    document.getElementById("profile_pic").src = profile.profilePic;
    document.getElementById("name").innerText = `${profile.firstName} ${profile.lastName}`;
    document.getElementById("signUpDate").innerText = profile.signUpDate;
    document.getElementById("rating").innerText = profile.rating;
    document.getElementById("name_").innerText = `${profile.firstName} ${profile.lastName}`;
    document.getElementById("gender").innerText = profile.gender;
    document.getElementById("professionalExperience").innerText = profile.professionalExperience;
    document.getElementById("category").innerText = profile.category;
    document.getElementById("serviceLocation").innerText = profile.serviceLocation;
    document.getElementById("pricing").innerText = profile.priceList;
    document.getElementById("email").innerText = profile.email;
    document.getElementById("phoneNumber").innerText = profile.phoneNumber;
};

function goBack(){
    window.history.back();
}