/*  
                    
    Eine Manuelle Eingabe wäre hier Unötig, die API die ich hier nutze ( DWD ) führt zum heutigen Zeitpunkt keine ordentlich funktionierende 
    Liste der Wetterstationen mit Stations IDs, deswegen wird hier eine Feste Stationnummer benutzt ( Berlin-Tempelhof )

*/

document.addEventListener('DOMContentLoaded', function() {
    // Funktion, um die Geolocation abzurufen
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;                    
                    // OpenStreetMap API aufrufen
                    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
                        .then(response => response.json())
                        .then(data => {
                            // Bezirk aus den Adressdaten extrahieren
                            const district = data.address.suburb || 'Bezirk nicht gefunden';
                            console.log("Bezirk:", district);
                            document.getElementById("jsStandortDiv").textContent = district;
                        })
                        .catch(error => {
                            console.error("Fehler beim Abrufen der Bezirksdaten:", error);
                        });
                },
                (error) => {
                    console.error("Fehler bei der Standortabfrage:", error);
                }                
            );
        }
    }
    getLocation();
});
document.addEventListener("DOMContentLoaded", async function () {
    try {
        const weatherDataToday = await fetchWeatherData('../ClimaQuest/dwd_days.php', 'weatherInfo');
        updateWeatherDisplay(weatherDataToday);

        const weatherForecast = await fetchWeatherData('../ClimaQuest/dwd_forecast.php', 'dayTodayTempText');
        updateTodayTemp(weatherForecast);

        const sevenDayForecast = await fetchWeatherData('../ClimaQuest/dwd_days.php');
        updateSevenDayForecast(sevenDayForecast);
    } catch (error) {
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
    }
});

async function fetchWeatherData(url, errorElementId = '') {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Netzwerkantwort war nicht ok.');
        }
        const weatherData = await response.json();
        if (weatherData.error) {
            console.error('Fehler: ', weatherData.error);
            if (errorElementId) {
                document.getElementById(errorElementId).innerText = `Fehler: ${weatherData.error}`;
            }
        } else {
            return weatherData;
        }
    } catch (error) {
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
        if (errorElementId) {
            document.getElementById(errorElementId).innerText = 'Fehler beim Abrufen der Wetterdaten.';
        }
    }
}

function updateWeatherDisplay(weatherData) {
    if (!weatherData) return;

    const {
        temperatureMin = 'Keine Daten',
        temperatureMax = 'Keine Daten',
        precipitation = '0',
        windSpeed = 'Keine Daten',
        windGust = 'Keine Daten',
        windDirection = 'Keine Daten',
        sunrise = 'Keine Daten',
        sunset = 'Keine Daten',
        moonrise = 'Keine Daten',
        moonset = 'Keine Daten',
    } = weatherData[0];

    document.getElementById('dayMINText').innerHTML = `Min: ${temperatureMin}°C`;
    document.getElementById('dayMAXText').innerHTML = `Max: ${temperatureMax}°C`;
    document.getElementById('dayPrecipitation').innerHTML = `${precipitation / 10}%`;
    document.getElementById('dayWindSpeed').innerHTML = `${windSpeed} Km/h`;
    document.getElementById('dayWindGust').innerHTML = `${windGust} Km/h`;
    document.getElementById('dayWindDirection').innerHTML = `Wind-Richtung`;    
    document.getElementById('daySunrise').innerHTML = `${sunrise} Uhr`;
    document.getElementById('daySunset').innerHTML = `${sunset} Uhr`;
    document.getElementById('dayMoonrise').innerHTML = `${moonrise} Uhr`;
    document.getElementById('dayMoonset').innerHTML = `${moonset} Uhr`;
}

function updateTodayTemp(weatherData) {
    if (!weatherData) return;

    const temperatureToday = weatherData.temperatureToday || 'Keine Daten';
    document.getElementById('dayTodayTempText').innerText = `${temperatureToday}°C`;
}

function updateSevenDayForecast(weatherData) {
    if (!weatherData) return;

    for (let i = 1; i < 5; i++) {
        const rawDate = weatherData[i]?.dayDate || 'Keine Daten';
        const formattedDate = formatDate(rawDate);

        const dayData = {
            weekday: getWeekday(weatherData[i].dayDate),
            date: formattedDate,
            temperatureMin: weatherData[i]?.temperatureMin || 'Keine Daten',
            temperatureMax: weatherData[i]?.temperatureMax || 'Keine Daten',
            windSpeed: weatherData[i]?.windSpeed || 'Keine Daten',
            windGust: weatherData[i]?.windGust || 'Keine Daten',
            windDirection: weatherData[i]?.windDirection || 'Keine Daten',
            precipitation: (weatherData[i]?.precipitation / 10) || '0',
        };
        daysBuilder(dayData);
    }
}



function formatDate(rawDate) {
    if (rawDate === 'Keine Daten') return rawDate;

    const dateObj = new Date(rawDate);
    const day = String(dateObj.getDate()).padStart(2, '0');
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const year = dateObj.getFullYear();

    return `${day}.${month}.${year}`;
}

function getWeekday(dateString) {
    const options = { weekday: 'long' };
    const date = new Date(dateString);
    return date.toLocaleDateString('de-DE', options); // Verwende 'de-DE' für deutsche Wochentagsnamen
}

function daysBuilder(dayData) {
    const innerContent = document.getElementById('innerContent');

    const dayTemplate = `
        <div class="Content_Days">
            <div class="innerDays">
                <div class="dateDiv">                
                    <p class="dayText">${dayData.weekday}</p>
                    <p class="dateText">${dayData.date}</p>
                </div>
                <div class="daysContent">
                    <div class="daysSlot">
                        <div class="daysTemp">${dayData.temperatureMax}°C</div>
                    </div>
                    <div class="daysSlot">                     
                        <div class="daysTemp">${dayData.temperatureMin}°C</div>
                    </div>
                </div>
            </div>
            <div class="innerDays">
                <div class="daysContent">
                    <div class="daysSlot">
                        <div class="daysWindImage"></div>
                        <div class="daysText">${dayData.windSpeed}</div><p style="font-size: 1ch; width: 70%; line-height: 25px;">km/h</p>
                    </div>
                    <div class="daysSlot">
                        <div class="daysMaxWindImage"></div>
                        <div class="daysText">${dayData.windGust} </div><p style="font-size: 1ch; width: 70%; line-height: 25px;">km/h</p>
                    </div>
                </div>
                <div class="daysContent">
                    <div class="daysSlot">
                        <div class="daysRichtungImage" style="background-image: url(assets/images/${dayData.windDirection}.png)"></div>
                        <div class="daysRichtung">Richtung</div>
                    </div>
                    <div class="daysSlot">
                        <div class="daysPrecipImage"></div>
                        <div class="daysText">${dayData.precipitation}%</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    innerContent.innerHTML += dayTemplate;
}

