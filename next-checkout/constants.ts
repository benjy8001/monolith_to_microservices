const dev = {
    BASE_URL: 'https://backend.micro.test/api/checkout/',
}
const prod = {
    BASE_URL: '',
}

export default {
    ...('development' === process.env.NODE_ENV ? dev : prod)
}
