<template>
  <div class="container">
    <table class="table border">
      <thead>
      <tr>
        <th scope="col">Дата</th>
        <th scope="col">Температура</th>
        <th scope="col">Облачность</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="weatherDay in weatherDays">
        <td>{{ weatherDay.date }}</td>
        <td>{{ weatherDay.temperature }}</td>
        <td>{{ weatherDay.clouds }}%</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      weatherDays: [],
    };
  },
  mounted() {
    fetch('http://localhost/api/v1/weather?city=moscow')
        .then(data => {
          return data.json()
        })
        .then(res => {
          this.weatherDays = res
        })
  }
};
</script>