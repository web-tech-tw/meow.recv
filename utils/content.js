import moment from 'moment'
import marked from 'marked'
import DOMPurify from 'dompurify'

function timeReadable(timestamp) {
  const microTimestamp = timestamp * 1000
  if (moment.now() - microTimestamp < 86400000) {
    return moment(microTimestamp).fromNow()
  } else {
    return moment(microTimestamp).format()
  }
}

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
  }
  return text.replace(/[&<>"']/g, function (m) {
    return map[m]
  })
}

function getMessageHTML(content) {
  content = escapeHtml(content)
  content = content.replace(/\n/g, '<br />')
  content = marked(content)
  content = DOMPurify.sanitize(content)
  return content
}

export { timeReadable, escapeHtml, getMessageHTML }
