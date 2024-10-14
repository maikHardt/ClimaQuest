<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/script.js"></script>
    <title>ClimaQuest</title>
</head>
<body>
    <div id="header">        
        <div id="innerheader">
            <div id="headertop">
                <form id="standortForm">
                    <div id="innerForm">
                        <input type="submit" id="standortButton" value="">
                        <div id="jsStandortDiv"> <!-- Standortermittlung wird per JS erstellt Wetter-Daten basieren auf Tempelhof --></div>
                    </div>                            
                </form>
            </div>
            <div id="headerbottom">
                <div id="headerbottomLeft">                   
                    <div id="dayDiv1">
                        <div id="dayTodayTemp"><p id="dayTodayTempText"></p></div>
                    </div>
                    <div id="dayDiv2">
                        <div id="dayMAX">
                            <p id="dayMAXText"></p>
                        </div>
                        <div id="dayMIN">
                            <p id="dayMINText"></p>
                        </div>
                    </div>                                       
                </div>                
                <div id="headerbottomRight">
                    <div class="daysDiv">
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="WindImage"></div>
                                <p id="dayWindSpeed"></p>
                            </div>                            
                        </div>
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="SunriseImage"></div>
                                <p id="daySunrise"></p>
                            </div>                            
                        </div>
                    </div>
                    <div class="daysDiv">
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="WindMaxImage"></div>
                                <p id="dayWindGust"></p>
                            </div>                            
                        </div>
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="SunsetImage"></div>
                                <p id="daySunset"></p>
                            </div>                            
                        </div>
                    </div>
                    <div class="daysDiv">
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="WindDirecImage"></div>
                                <p id="dayWindDirection"></p>
                            </div>                            
                        </div>
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="MoonriseImage"></div>
                                <p id="dayMoonrise"></p>
                            </div>                            
                        </div>
                    </div>
                    <div class="daysDiv">
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="PrecipImage"></div>
                                <p id="dayPrecipitation"></p>
                            </div>                            
                        </div>
                        <div class="BoxDiv">
                            <div class="BoxinnerDiv">
                                <div id="MoonsetImage"></div>
                                <p id="dayMoonset"></p>
                            </div>                            
                        </div> 
                    </div>                                      
                </div>
            </div>
        </div>
    </div>
    <div id="Content">
        <div id="innerContent">
<!-- Die Tage Div-Container werden per JS erstellt -->
        </div>
    </div>
</body>
</html>