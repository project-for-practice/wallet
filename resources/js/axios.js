import axios from "axios";

axios.defaults.baseURL = "https://admni.usdtexchange.co";

const token = localStorage.getItem("auth_token");
if (token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response.status === 401) {
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

export default axios;
