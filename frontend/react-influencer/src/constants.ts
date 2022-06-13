const dev = {
    BASE_URL: 'https://backend.micro.test/api/influencer/',
    CHECKOUT_URL: 'https://frontend-influencer.micro.test'
}
const prod = {
    BASE_URL: '',
    CHECKOUT_URL: ''
}

export default {
    ...('development' === process.env.NODE_ENV ? dev : prod)
}
