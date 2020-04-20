"use strict";

function validateAll(event) {
	var formIsValid = true;
	
	if(!$("#firstName").val().trim())
	{
		$("#firstNameError").removeClass("hidden");
		formIsValid = false;
	}
	if(!$("#lastName").val().trim())
	{
		$("#lastNameError").removeClass("hidden");
		formIsValid = false;
	}
	var email = $("#email").val().trim();
	if(!email)
	{
		$("#emailError").text("Veuillez saissir votre email");
		$("#emailError").removeClass("hidden");
		formIsValid = false;
	}
	else if(!emailIsValid (email))
	{
		$("#emailError").text("Veuillez saissir une adresse mail valide");
		$("#emailError").removeClass("hidden");
		formIsValid = false;
	}
	var dateText = $("#birthDate").val();
	if(!dateText)
	{
		$("#birthDateError").text("Veuillez saissir votre date de naissance");
		$("#birthDateError").removeClass("hidden");
		formIsValid = false;
	}
	else if (getAge(dateText) < 18)
	{
		$("#birthDateError").text("Vous devez être majeur de 18 ans pour pouvoir vous inscrire");
		$("#birthDateError").removeClass("hidden");
		formIsValid = false;
	}
	var phone = $("#phone").val().trim();
	if(!phone)
	{
		$("#phoneError").text("Veuillez saissir votre numéro de téléphone");
		$("#phoneError").removeClass("hidden");
		formIsValid = false;
	}
	else if(!phoneIsValid (phone))
	{
		$("#phoneError").text("Veuillez saissir un numéro de téléphone valide");
		$("#phoneError").removeClass("hidden");
		formIsValid = false;
	}
	if(!$("#country").val().trim())
	{
		$("#countryError").removeClass("hidden");
		formIsValid = false;
	}
	var question = $("#question").val().trim();
	if(!question)
	{
		$("#questionError").removeClass("hidden");
		formIsValid = false;
	}
	else if(question.length < 15)
	{
		$("#questionError").text("La question doit comporter minimum 15 caractères");
		$("#questionError").removeClass("hidden");
		formIsValid = false;
	}

	if(!formIsValid) {
		event.preventDefault();
	}
}

function getAge(DOB) {
    var today = new Date();
    var birthDate = new Date(DOB);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age = age - 1;
    }
	return age;
}

function emailIsValid (email) {
	return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
}
function phoneIsValid (phone) {
	return /^(01|02|03|04|05|06|07|08|09)[0-9]{8}/gi.test(phone)
}

$(function()
{
	$("#inscriptionForm").submit(validateAll);
});