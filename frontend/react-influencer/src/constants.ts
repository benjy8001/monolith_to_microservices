const dev = {
    BASE_URL: 'https://backend.micro.test/api',
    USERS_URL: 'https://backend-users.micro.test/api',
    CHECKOUT_URL: 'https://frontend-influencer.micro.test'
}
const prod = {
    BASE_URL: '',
    USERS_URL: '',
    CHECKOUT_URL: ''
}

export default {
    ...('development' === process.env.NODE_ENV ? dev : prod)
}