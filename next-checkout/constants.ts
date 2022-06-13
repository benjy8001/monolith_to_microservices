const dev = {
    BASE_URL: 'https://backend.micro.test/api/checkout/',
    STRIPE_KEY: 'pk_test_51L81x7FiM03bCHKbWAXvkeGsuZc0vK0VfXZSbt9FL7ADpBOlGzRLnu2RqTxlV55IzJD7eBKO2Xzc3krA2tJsT8f500Y5knydPZ',
}
const prod = {
    BASE_URL: '',
    STRIPE_KEY: '',
}

export default {
    ...('development' === process.env.NODE_ENV ? dev : prod)
}
