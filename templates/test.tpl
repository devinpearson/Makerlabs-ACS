{$header}

        <!-- Make sure all your bars are the first things in your <body> -->
        <header class="bar bar-nav">
            <h1 class="title">MakerLabs Access Control</h1>
        </header>
        {$nav}
        <!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
        <div class="content">
            <FORM METHOD=POST enctype="multipart/form-data" ACTION="{$url}">
                <!--Access Token: <input type="text" name="my_access_token" value="<?php //echo($my_access_token);   ?>"><br>-->
                <!--Device ID: <input type="text" name="my_device" value="<?php //echo($my_device);   ?>"><br>-->
                <!--<label for="level_high">HIGH</label><input type="radio" id="level_high" name="level" value="HIGH" checked="checked"/>-->
                <!--<label for="level_low">LOW</label><input type="radio" id="level_low" name="level" value="LOW"/>-->
                <!--<input type="Submit" name="Submit" value="Open" style="width:60px;height:30px;">-->
                <button class="btn btn-positive btn-block" type="submit">Open Gate</button>
            </form>
                <a href="#myModalexample"><button class="btn btn-negative btn-block" type="submit">Open Model</button></a>
<div id="myModalexample" class="modal">
  <header class="bar bar-nav">
    <a class="icon icon-close pull-right" href="#myModalexample"></a>
    <h1 class="title">Modal</h1>
  </header>

  <div class="content">
    <p class="content-padded">The contents of my modal go here. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
  </div>
</div>
        </div>
{$footer}