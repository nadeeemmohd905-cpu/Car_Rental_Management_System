<link rel="stylesheet" type="text/css" href="assets/css/chat-bot.css" />
<header>
  <div class="default-header">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <div class="logo"> <a href="index.php"><img src="assets/images/logo.png" alt="image"/></a> </div>
        </div>
        <div class="col-sm-9 col-md-10">
          <div class="header_info">
         <?php
         $sql = "SELECT EmailId,ContactNo from tblcontactusinfo";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
foreach ($results as $result) {
$email=$result->EmailId;
$contactno=$result->ContactNo;
}
?>   

            <div class="header_widgets">
              <div class="circle_icon"> <i class="fa fa-envelope" aria-hidden="true"></i> </div>
              <p class="uppercase_text">For Support Mail us : </p>
              <a href="mailto:<?php echo htmlentities($email);?>"><?php echo htmlentities($email);?></a> </div>
            <div class="header_widgets">
              <div class="circle_icon"> <i class="fa fa-phone" aria-hidden="true"></i> </div>
              <p class="uppercase_text">Service Helpline Call Us: </p>
              <a href="tel:<?php echo htmlentities($contactno);?>"><?php echo htmlentities($contactno);?></a> </div>
            <div class="social-follow">
            
            </div>
   <?php   if(strlen($_SESSION['login'])==0)
	{	
?>
 <div class="login_btn"> <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login / Register</a> </div>
<?php }
else{ 

echo "Welcome To Car rental portal";
 } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Navigation -->
  <nav id="navigation_bar" class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div class="header_wrap">
        <div class="user_login">
          <ul>
            <li class="dropdown"> <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle" aria-hidden="true"></i> 
<?php 
$email=$_SESSION['login'];
$sql ="SELECT FullName FROM tblusers WHERE EmailId=:email ";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
	{

	 echo htmlentities($result->FullName); }}?>
   <i class="fa fa-angle-down" aria-hidden="true"></i></a>
              <ul class="dropdown-menu">
           <?php if($_SESSION['login']){?>
            <li><a href="profile.php">Profile Settings</a></li>
              <li><a href="update-password.php">Update Password</a></li>
            <li><a href="my-booking.php">My Booking</a></li>
            <li><a href="post-testimonial.php">Post a Testimonial</a></li>
          <li><a href="my-testimonials.php">My Testimonial</a></li>
            <li><a href="logout.php">Sign Out</a></li>
            <?php } ?>
          </ul>
            </li>
          </ul>
        </div>
        <div class="header_search">
          <div id="search_toggle"><i class="fa fa-search" aria-hidden="true"></i></div>
          <form action="search.php" method="post" id="header-search-form">
            <input type="text" placeholder="Search..." name="searchdata" class="form-control" required="true">
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
      </div>
      <div class="collapse navbar-collapse" id="navigation">
        <ul class="nav navbar-nav">
          <li><a href="index.php">Home</a>    </li>
          	 
          <li><a href="page.php?type=aboutus">About Us</a></li>
          <li><a href="car-listing.php">Car Listing</a>
          <li><a href="page.php?type=faqs">FAQs</a></li>
          <li><a href="contact-us.php">Contact Us</a></li>

        </ul>
      </div>
    </div>
  </nav>
  <!-- Navigation end --> 
  
</header>

<button id="chatbot-toggle-btn">Chat</button>
<div class="chatbot-popup" id="chatbot-popup">
    <div class="chat-header">
        <span>Chatbot | Car Rental Management</span>
        <button id="close-btn">&times;</button>
    </div>
    <div class="chat-box" id="chat-box"></div>
    <div class="chat-input">
        <input type="text" id="user-input" placeholder="Type a message...">
        <button id="send-btn">Send</button>
    </div>
    <div class="copyright">
        <a href="https://www.example.com/" target="_blank">© 2024 Car Rental Management</a>
    </div>
</div>

<script>const responses = {
    "hello": ["Hi there! How can I assist you today?", "Hello! How can I help you?"],
    "coding hubs": ["Here you will get Notes, Ebooks, project source Code, Interview questions. Visit Coding Hubs.", "<a href='https://thecodinghubs.com' target='_blank'>Visit Website</a>"],
    "how are you": ["I'm just a bot, but I'm here to help you!", "I'm doing well, thank you! How can I assist you today?"],
    "help": ["Sure, how can I assist you?", "What do you need help with today?"],
    "bye": ["Goodbye! Have a great day!", "See you later! Take care!"],
    "default": ["I'm sorry, I didn't understand that. Do you want to connect with an expert?", "Could you please rephrase that? Or would you like to speak with an expert?"],
    "expert": ["Great! Please wait a moment while we connect you with an expert.", "Connecting you with an expert now. Please hold on..."],
    "no": ["Okay, if you change your mind, just let me know!", "No worries! I'm here if you need anything else."]
};

let context = [];
let userName = '';

document.getElementById('chatbot-toggle-btn').addEventListener('click', toggleChatbot);
document.getElementById('close-btn').addEventListener('click', toggleChatbot);
document.getElementById('send-btn').addEventListener('click', sendMessage);
document.getElementById('user-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

function toggleChatbot() {
    const chatbotPopup = document.getElementById('chatbot-popup');
    chatbotPopup.style.display = chatbotPopup.style.display === 'none' ? 'block' : 'none';
}

function sendMessage() {
    const userInput = document.getElementById('user-input').value.trim();
    if (userInput !== '') {
        appendMessage('user', userInput);
        context.push(userInput.toLowerCase());
        respondToUser(userInput.toLowerCase());
        document.getElementById('user-input').value = '';
    }
}

function respondToUser(userInput) {
    if (userInput.includes("my name is") || userInput.includes("i'm") || userInput.includes("myself")) {
        extractUserName(userInput);
        if (userName) {
            appendMessage('bot', `Hey ${userName}, how can I help you?`);
            return;
        }
    } else if (userInput.includes("rent a car") || userInput.includes("car for rent") || userInput.includes("book a car")) {
        appendMessage('bot', "Sure! Please let me know the type of car you're looking for, and the rental period.");
        return;
    } else if (userInput.includes("price")) {
        appendMessage('bot', "Our prices vary depending on the car model and rental duration. Could you specify the car type and rental period?");
        return;
    } else if (userInput.includes("available cars")) {
        appendMessage('bot', "We have a wide range of cars available including SUVs, Sedans, and Hatchbacks. Which type are you interested in?");
        return;
    } else if (userInput.includes("location")) {
        appendMessage('bot', "Please provide your current location so we can find the nearest available cars for you.");
        return;
    }

    const keyword = matchKeyword(userInput);
    const responseArray = responses[keyword] || responses["default"];
    const response = selectRandomResponse(responseArray);

    setTimeout(function() {
        appendMessage('bot', response);
        if (response === responses["default"][0]) {
            displayExpertOptions();
        }
    }, 500);
}

function matchKeyword(userInput) {
    const keywords = Object.keys(responses);
    for (let i = 0; i < keywords.length; i++) {
        if (userInput.includes(keywords[i])) {
            return keywords[i];
        }
    }
    return "default";
}

function selectRandomResponse(responseArray) {
    return responseArray[Math.floor(Math.random() * responseArray.length)];
}

function appendMessage(sender, message) {
    const chatBox = document.getElementById('chat-box');
    const messageElement = document.createElement('div');
    messageElement.classList.add(sender === 'user' ? 'user-message' : 'bot-message');
    messageElement.innerHTML = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function displayExpertOptions() {
    const chatBox = document.getElementById('chat-box');
    
    const buttonYes = document.createElement('button');
    buttonYes.textContent = '✔ Yes';
    buttonYes.onclick = function() {
        appendMessage('bot', responses["expert"][0]);
    };
    
    const buttonNo = document.createElement('button');
    buttonNo.textContent = '✖ No';
    buttonNo.onclick = function() {
        appendMessage('bot', responses["no"][0]);
    };

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('button-container');
    buttonContainer.appendChild(buttonYes);
    buttonContainer.appendChild(buttonNo);
    chatBox.appendChild(buttonContainer);
}

function extractUserName(userInput) {
    const nameRegex = /(my name is|i'm|myself)\s+([a-zA-Z]+)/i;
    const match = userInput.match(nameRegex);
    if (match && match[2]) {
        userName = match[2];
    }
}

</script>