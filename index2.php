<html>
<head>
<link href="style.css" rel="stylesheet"/>
<link href="jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript' src='jquery-ui-1.10.3.custom.min.js'></script>

<script type='text/javascript' src='db.js'></script>
<script type='text/javascript' src='index.js'></script>
<script type='text/javascript' src='user.model.js'></script>

<script type='text/javascript'>

window.onload = function() {

// from http://stackoverflow.com/a/7826782/1183453
  var args = document.location.search.substring(1).split('&');
  argsParsed = {};
  for (var i=0; i < args.length; i++)
  {
      arg = unescape(args[i]);

      if (arg.length == 0) {
        continue;
      }

      if (arg.indexOf('=') == -1)
      {
          argsParsed[arg.replace(new RegExp('/$'),'').trim()] = true;
      }
      else
      {
          kvp = arg.split('=');
          argsParsed[kvp[0].trim()] = kvp[1].replace(new RegExp('/$'),'').trim();
      }
  }

  if ('q' in argsParsed) {
    // came from mindmargin
    $('#home').hide();
    $('#questionnaire').show();
  } else if('x' in argsParsed) {
    // came from traditional
    $('#home').hide();
    $('#questionnaire').show();    
  }
}

function init_experiment() {
  // randomize p1 (horizontal) and p2 (vertical) probability
  var prototypes = shuffle(['horizontal','vertical']);
  var curr_prototype = prototypes[0];

  // redirect to p1
  if (curr_prototype == 'horizontal') {
      window.location.replace("welcome?userid="+USER_ID +"&username="+NICKNAME);
  }

  // redirect to p2
  if (curr_prototype == 'vertical') {
      window.location.replace("start?userid="+USER_ID +"&username="+NICKNAME);
  }

}

function shuffle(array) {
  var currentIndex = array.length
    , temporaryValue
    , randomIndex
    ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

</script>

</head>

<body>

<!-- 
- We want to learn what you guys think about this article/this issue
- see whether people engage with existing comments => article presents one view of the story.... and comments expand view (what about this/that?)
- ask people what their own position is at end and see if those were influenced by comments .... if they took into account counterpoints mentioned in the comments == designing so that people's focus is on the content
-->

<!-- HOME -->
<div id="home">
<h1 class="title">Your Opinion Matters</h1>
<div style="font-size:20px;">What are people thinking about...? Where does your opinion lie on the spectrum?</div>
<!-- button from HOME to INSTRUCTIONS -->
</br>
<img class="homeimg" src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQ7q-diIV5gBd2EVXNu3MuM6HPqXqPjDfvtiy6cX3OoO0t9LIPm">
</br></br>
<button class="next-btn" onclick="$('#home').hide();$('#consent').show();">Find Out</button></br>
<div id="bottom">
</div>
</div>

<!-- CONSENT FORM -->
<div id="consent" style="display:none">
<div style="margin-left:25%;margin-right:25%;">
<h1 class="title">Statement of Informed Consent</h1>
<div class="content" style="text-align:left;overflow-y:scroll;height:50%">
<ul>You're about to participate in a research study. Your contribution to our research allows us to learn more about <b>how opinions differ on TFA</b>.</ul>
<ul><i>Please read the following information carefully before proceeding.</i></ul>
<ul><h4>Why we are doing this research</h4></ul>
<ul>We are trying to understand how PEOPLES OPINONS DIFFER ON THIS ISSUE. We are also trying to understand how different commenting systems perform and affect engagement. </ul>
<ul><h4>What you will have to do</h4></ul>
<ul>You will be shown an article of a controversial NATURE. Feel free to make comments and reply to comments as you wish. There will be questions regarding your opinion of the article afterwards. </ul>
<ul><h4>Potential risks</h4></ul>
<ul>There are no risks anticipated in taking part in this study and you are free to leave at any time. </ul>
<ul><h4>Duration</h4></ul>
<ul>Approximately 10 minutes.</ul>
<ul><h4>To contact the researcher</h4></ul>
<!-- <ul>If you have questions or concerns about this research, please contact prof. Krzysztof Gajos, Maxwell Dworkin 251, 33 Oxford St, Cambridge, MA 02138, kgajos@seas.harvard.edu</ul>
 --><ul>Whom to contact about your rights in this research, for questions, concerns, suggestions, or complaints that are not being addressed by the researcher, or research-related harm: Committee on the Use of Human Subjects in Research at Harvard University, 1414 Massachusetts Avenue, Second Floor, Cambridge, MA 02138. Phone: 617-496-2847 (CUHS). Email: cuhs@fas.harvard.edu.</ul>
<ul>By clicking the button below you confirm that you have read and understood the above and agree to take part in this research. Your participation is voluntary and you are free to leave the experiment at any time by simply closing the web browser.</ul>
</div>
</div>
<!-- button from CONSENT FORM to INSTRUCTIONS -->
<button class="next-btn" onclick="$('#consent').hide();$('#demographics').show();">I agree</button>
</br></br>
<div id="bottom">
</div>
</div>

<!-- DEMOGRAPHICS -->
<div id='demographics' style='display:none'>
<div style="margin-left:25%;margin-right:25%;">
<h1 class="title">Some information</h1>
<div class="content" style="text-align:left;">
<ul>Before we start, please answer the following questions.</ul>

<b>How old are you?</b></br></br>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="age" value="eighteen">18-25 &nbsp;&nbsp;&nbsp;
<input type="radio" name="age" value="twentysix">26-35 &nbsp;&nbsp;&nbsp;
<input type="radio" name="age" value="thirtysix">36-45 &nbsp;&nbsp;&nbsp;
<input type="radio" name="age" value="old">45+

<br><br><b>Your gender?</b></br></br>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="gender" value="male">male &nbsp;&nbsp;&nbsp;
<input type="radio" name="gender" value="female">female &nbsp;&nbsp;&nbsp;


<br><br><b>Your study concentration?</b></br></br>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="concentration" value="arts">Arts + Humanities &nbsp;&nbsp;&nbsp;
<input type="radio" name="concentration" value="science">Science &nbsp;&nbsp;&nbsp;
<input type="radio" name="concentration" value="social">Social Science &nbsp;&nbsp;&nbsp;
<input type="radio" name="concentration" value="seas">School of Engineering and Applied Sciences &nbsp;&nbsp;&nbsp;

<br><br><b>Are you left or right handed?</b></br></br>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="handed" value="left">left handed&nbsp;&nbsp;&nbsp;
<input type="radio" name="handed" value="right">right handed&nbsp;&nbsp;&nbsp;

<br><br><b>How often do you read news articles?</b></br></br>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="reading" value="daily">daily &nbsp;&nbsp;&nbsp;
<input type="radio" name="reading" value="weekly">weekly &nbsp;&nbsp;&nbsp;
<input type="radio" name="reading" value="monthly">monthly &nbsp;&nbsp;&nbsp;
<input type="radio" name="reading" value="almostnever">almost never


</div>
<button class="next-btn" onclick="submit_demographics();">Let's go!</button>
</div>
</div>

<!-- INSTRUCTIONS -->
<div id="instructions" style="display:none;">
<div style="margin-left:25%;margin-right:25%;">
<h1 class="title">Instructions</h1>
<div class="content" style="text-align:left;">
<ul>This test will investigate where you stand in THIS ISSUE. You will be shown a controversial article. Your task is to <b>read</b> the article and to <b>interact</b> with it as you see fit.</ul>
<ul>A 10 minute timer will be put in place. If you finish reading after 2 minutes, you can go to the opinion section by clicking the 'Finish Reading' button. </ul>
<ul>The opinion section will gauge where you stand on this issue.</ul>
<ul>Your opinion on the spectrum will be shown at the end, as well as how many others thought the same way you did. </ul>
<ul><b>Please feel free to add comments to express your opinion.</b></ul>
<ul>You can comment as <b>anonymous</b> or using a custom nickname:</ul>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="nickname" value="anonymous">anonymous &nbsp;&nbsp;&nbsp;
<input type="radio" name="nickname" value="custom">Nickname: <input id='nickname' type='text'> &nbsp;&nbsp;&nbsp;
</div>
</div>
<!-- button from INSTRUCTIONS to EXPERIMENT -->
<button class="next-btn" onclick="submit_instructions();">Start</button>
</br></br>
</div>
<div id="bottom" style="display:none;">
</div>

<!-- QUESTIONNAIRE  -->
<div id="questionnaire" style="display:none;">
<div style="margin-left:25%;margin-right:25%;">
<h1 class="title">A bit of inquiry...</h1>
<div class="content" style="text-align:left;">
<div style="font-size:20px;">Your answers will tell us more. Thanks!</div>
<b>Which interface did you prefer?</b></br></br>
<form id='interface_form'>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="preference" value="ribbons" id='ribradio'><img src='gfx/ribbon_home.png' width=200 onclick='$("#ribradio").click()'> &nbsp;&nbsp;&nbsp;
<input type="radio" name="preference" value="commandmaps" id='cmdmapradio'><img src='gfx/commandmap.png' width=200 onclick='$("#cmdmapradio").click()'>
</form>
</br></br>
<b>What device are you using on your computer?</b></br></br>
<form id='device_form'>
&nbsp;&nbsp;&nbsp;
<input type="radio" name="device" value="mouse">Mouse &nbsp;&nbsp;&nbsp;
<input type="radio" name="device" value="trackpad">Trackpad &nbsp;&nbsp;&nbsp;
<input type="radio" name="device" value="trackball">Trackball &nbsp;&nbsp;&nbsp;
<input type="radio" name="device" value="other">Other
</form>
</br></br>
<b>If you are using Mechanical Turk, please specify your MTurk ID!</b>
<form id='mturk'>
<input type='text' name='mturk'>
<br>
<b>..and enter this code in MTurk:</b> <span id='c'>32f332ds</span>
</form>
</br></br>
<b>Comments?</b>
<div style="color:grey;font-size:small"><i>technical difficulties? thoughts in general?</i></div></br>
<form id='comments_form'>
<textarea name="comments" id="comments" cols="25" rows="5">
</textarea><br><br>
</form>
<button class="next-btn" onclick="Q.submit();" style="margin-left:5px;">Submit</button>
</div>
</div>
</div>

<!-- FINISH end page -->
<div id="finish" style="display:none;">
    <h1>Thank you for participating in our study! We really appreciate your help.</h1>

    <h2 class="title">37% of people agree with your stance! </h2></br><h2 id='ranking'>Spread your opinions around. You are special! </h2></br>
    

    <h2>Now see if <b>your friends</b> think the same! Send them this link: http://tinyurl.com/opinionspectrum or</h2>
  
    <script>
    function fbShare(url, title, descr, image, winWidth, winHeight) {
        var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }
    </script>

    <a href="javascript:fbShare('http://tinyurl.com/opinionspectrum', 'We want to know the current opinion of TFA!', 'Where do you stand on this issue?', 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQ7q-diIV5gBd2EVXNu3MuM6HPqXqPjDfvtiy6cX3OoO0t9LIPm', 520, 350)"><img src='gfx/fbsharebtn.png'></a>
</div> 

</body>