import axios from "../axios";

export const getLogins = () => axios.get("/login-usuarios");

export const getLogin = (id) => axios.get(`/login-usuarios/${id}`);

export const createLogin = (data) => axios.post("/login-usuarios", data);

export const updateLogin = (id, data) => axios.put(`/login-usuarios/${id}`, data);

export const deleteLogin = (id) => axios.delete(`/login-usuarios/${id}`);
