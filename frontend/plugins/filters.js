import Vue from 'vue'

// Formats the datetime value to a more human-readable text
Vue.filter('date_format', function (date, text) {
  return date
    ? new Date(date).toUTCString().substring(5, 16).toUpperCase()
    : text || '\xA0'
})

// Returns the Initials of some name
Vue.filter('initials', function (name) {
  if (name) {
    const n = name.split(' ')
    return n[0].substring(0, 1) + n[n.length - 1].substring(0, 1)
  }
  return ''
})
