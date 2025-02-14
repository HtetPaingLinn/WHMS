import axios from 'axios';
// import { Townships } from "../constants/townships";

const API_BASE = 'http://localhost:3001';



export interface Report {
    id?: number;
    lat: number;
    lng: number;
    description: string;
    township: string;
    petType: 'lost' | 'injured';
    status: string;
    pending: string;
    invoice_num: number;
}

export interface Shelter {
    id: number;
    name: string;
    lat: number;
    lng: number;
    contact: string;
}

export interface Delivery {
    id: number;
    lat: number;
    lng: number;
    shelterId: number;
    invoiceNum: number[];
    status: string;
    shopName: string[];
    driverName: string;
    driverContact: string;
}

export const api = {
    // Reports
    getReports: () => axios.get<Report[]>(`${API_BASE}/reports`),
    submitReport: (report: Report) => axios.post<Report>(`${API_BASE}/reports`, report),
    updateReport: (id: number, status: string) =>
        axios.patch(`${API_BASE}/reports/${id}`, { status }),

    // Shelters
    getShelters: () => axios.get<Shelter[]>(`${API_BASE}/shelters`),
    submitShelter: (shelter: Shelter) => axios.post<Shelter>(`${API_BASE}/shelters`, shelter),

    // Deliveries
    getDeliveries: () => axios.get<Delivery[]>(`${API_BASE}/deliveries`),
    submitDelivery: (delivery: Delivery) =>
        axios.post<Delivery>(`${API_BASE}/deliveries`, delivery),
    updateDelivery: (id: number, status: string) =>
        axios.patch(`${API_BASE}/deliveries/${id}`, { status }),
};
