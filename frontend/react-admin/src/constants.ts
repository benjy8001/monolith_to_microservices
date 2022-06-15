const dev = {
    BASE_URL: 'https://backend.micro.test/api',
    USERS_URL: 'https://backend-users.micro.test/api'
}
const prod = {
    BASE_URL: '',
    USERS_URL: ''
}

export default {
    ...('development' === process.env.NODE_ENV ? dev : prod)
}
