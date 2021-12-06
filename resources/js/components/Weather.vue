<template>
    <div class="text-white mb-8">
        <div class="places-input text-gray-800">
            <input type="search" id="address" class="form-control placeholder-gray-600 border rounded-lg w-full h-8 px-2 text-sm text-gray-700" placeholder="Choose a city..." @keyup.enter="fetchData"/>
        </div>
        <div class="weather-container font-sans md:w-128 max-w-lg rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-8">
            <div class="current-weather flex items-center justify-between px-6 py-8">
                <div class="flex flex-col md:flex-row items-center">
                    <div>
                        <div class="text-6xl font-semibold">{{ currentTemperature.actual }}°C</div>
                        <div>Feels like {{ currentTemperature.feels }}°C</div>
                    </div>
                    <div class="md:mx-5">
                        <div class="font-semibold">{{ currentTemperature.summary }}</div>
                        <div class="text-grey-400">{{ location }}</div>
                    </div>
                </div>
                <div>
                    <img :src="'http://openweathermap.org/img/wn/' + currentTemperature.icon + '.png'" alt="icon">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                currentTemperature: {
                    actual: '',
                    feels: '',
                    summary: '',
                    icon: '',
                },
                daily: [],
                location: ''
            }
        },
        mounted() {
            this.currentLocation();
        },
        methods: {
            fetchData() {
                this.location = document.getElementById('address').value
                fetch(`/api/get_weather`, {
                        method: 'post',
                        headers: {
                            'Content-Type' : 'application/json'
                        },
                        body: JSON.stringify({location: this.location})
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.currentTemperature.actual = Math.round(data.main.temp)
                        this.currentTemperature.feels = Math.round(data.main.feels_like)
                        this.currentTemperature.summary = this.toKebabCase(data.weather[0].description)
                        this.currentTemperature.icon = this.toKebabCase(data.weather[0].icon)
                    })
            },
            currentLocation() {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        fetch(`/api/get_current_location_weather`, {
                                method: 'post',
                                headers: {
                                    'Content-Type' : 'application/json'
                                },
                                body: JSON.stringify({lat: position.coords.latitude, lon: position.coords.longitude})
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.currentTemperature.actual = Math.round(data.main.temp)
                                this.currentTemperature.feels = Math.round(data.main.feels_like)
                                this.currentTemperature.summary = this.toKebabCase(data.weather[0].description)
                                this.currentTemperature.icon = this.toKebabCase(data.weather[0].icon)
                                this.location = data.name
                            })
                    },
                    error => {
                        console.log(error.message);
                    },
                )
            },
            toKebabCase(stringToConvert) {
                return stringToConvert.split(' ').join('-')
            }
        }
    }
</script>
