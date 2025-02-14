import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, Marker, Popup, useMapEvents, Polyline, useMap } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import axios from 'axios';
import { YANGON_TOWNSHIPS } from './constants/townships'; // You'll need to adjust this import
import { api, Report, Shelter, Delivery } from './mock_backend/api';
import ReactDOM from 'react-dom';


const DefaultIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

const RedIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

// Add Green Icon for driver location
const GreenIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

// Add Purple Icon for deliveries
const PurpleIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

const customDriverIcon = L.icon({
    iconUrl: "/truck-icon.png", // Use your own truck image
    iconSize: [32, 32],
    iconAnchor: [16, 32],
});

L.Marker.prototype.options.icon = DefaultIcon;

interface RouteInfo {
    deliveryId: number;
    shopName: string;
    totalTime: number;
    reports: Array<{
        description: string;
        distance: number;
    }>;
}

const location_gp1: string[] = [YANGON_TOWNSHIPS.Tarmwe, YANGON_TOWNSHIPS.Bahan, YANGON_TOWNSHIPS.Yankin, YANGON_TOWNSHIPS.Dagon, YANGON_TOWNSHIPS.Thaketa];
const location_gp2: string[] = [YANGON_TOWNSHIPS.ThingyanKyun, YANGON_TOWNSHIPS.SouthOkkalapa, YANGON_TOWNSHIPS.NorthDagon, YANGON_TOWNSHIPS.SouthDagon, YANGON_TOWNSHIPS.EastDagon]
const location_gp3: string[] = [YANGON_TOWNSHIPS.Hlaing, YANGON_TOWNSHIPS.Insein, YANGON_TOWNSHIPS.HlaingTharYar, YANGON_TOWNSHIPS.Sanchaung, YANGON_TOWNSHIPS.Kamaryut]
const location_gp4: string[] = [YANGON_TOWNSHIPS.Lanmadaw, YANGON_TOWNSHIPS.Latha, YANGON_TOWNSHIPS.PazundaungTownship, YANGON_TOWNSHIPS.Botahtaung, YANGON_TOWNSHIPS.Kyauktada, YANGON_TOWNSHIPS.MingalarTaungNyunt]


// Create a separate component for delivery info
const DeliveryInfo: React.FC<{ routeInfos: RouteInfo[] }> = ({ routeInfos }) => {
    const deliveryInfoContainer = document.getElementById('delivery-info');

    if (deliveryInfoContainer) {
        return ReactDOM.createPortal(
            <div>
                {routeInfos.map((info) => (
                    <div
                        key={info.deliveryId}
                        className="mb-5 p-4 bg-gray-50 rounded-lg"
                    >
                        <h4 className="flex items-center mb-3 font-bold">
                            Delivery {info.deliveryId}
                            <span
                                className="inline-block w-5 h-1 ml-2 rounded"
                                style={{
                                    backgroundColor: `hsl(${(info.deliveryId * 137) % 360}, 70%, 50%)`
                                }}
                            />
                        </h4>
                        <p className="mb-2"><strong>Total Time:</strong> {info.totalTime} minutes</p>
                        <p className="mb-2"><strong>Assigned Deliveries:</strong></p>
                        <ul className="list-disc pl-5">
                            {info.reports.map((report, index) => (
                                <li key={index} className="mb-1">
                                    {report.description} ({report.distance.toFixed(2)} km)
                                </li>
                            ))}
                        </ul>
                    </div>
                ))}
            </div>,
            deliveryInfoContainer
        );
    }
    return null;
};

const MapComponent: React.FC = () => {
    const [position, setPosition] = useState<[number, number]>([16.8397, 96.1444]);
    const [reports, setReports] = useState<Report[]>([]);
    const [shelters, setShelters] = useState<Shelter[]>([]);
    const [userLocation, setUserLocation] = useState<[number, number] | null>(null);
    const [showForm, setShowForm] = useState(false);
    const [selectedPosition, setSelectedPosition] = useState<[number, number] | null>(null);
    const [formType, setFormType] = useState<'shelter' | 'report' | null>(null);
    const [routePolyline, setRoutePolyline] = useState<[number, number][]>([]);
    const [travelTime, setTravelTime] = useState<number | null>(null);
    const [isLoading, setIsLoading] = useState(false);
    const [optimizedRoute, setOptimizedRoute] = useState<[number, number][]>([]);
    const [totalTravelTime, setTotalTravelTime] = useState<number | null>(null);

    const [optimizedWaypoints, setOptimizedWaypoints] = useState<any[]>([]);
    const [routeSegments, setRouteSegments] = useState<Array<[number, number][]>>([]);
    const [deliveries, setDeliveries] = useState<Delivery[]>([]);

    //  for calculation optimal delivery roy
    const [deliveryRoutes, setDeliveryRoutes] = useState<{ [key: number]: [number, number][] }>({}); // Store routes for each delivery

    const [routeInfos, setRouteInfos] = useState<RouteInfo[]>([]);
    const [isInfoMinimized, setIsInfoMinimized] = useState(false);

    // filtering invoice number
    const [filteredDeliveryId, setFilteredDeliveryId] = useState<number | null>(null);

    // Add new state for time complexity
    const [algorithmStats, setAlgorithmStats] = useState<{
        timeComplexity: string;
        executionTime: number;
        overallProximityStats: string;
        routeProximityStats: { [key: number]: string };
    }>({
        timeComplexity: '',
        executionTime: 0,
        overallProximityStats: '',
        routeProximityStats: {}
    });

    // Add new state for algorithm stats minimized
    const [isAlgoStatsMinimized, setIsAlgoStatsMinimized] = useState(false);

    // Add new state for selected driver
    const [selectedDriverName, setSelectedDriverName] = useState<string | null>(null);

    // Add new state for animation
    const [animationRoute, setAnimationRoute] = useState<[number, number][]>([]);

    // Add new state for driver card
    const [showDriverCard, setShowDriverCard] = useState(false);
    const [isDriverCardMinimized, setIsDriverCardMinimized] = useState(false);

    // Add event listener for invoice search
    useEffect(() => {
        const handleInvoiceSearch = (event: CustomEvent) => {
            const searchedInvoice = event.detail.invoiceNumber;

            console.log("Searching for invoice:", searchedInvoice); // Debugging log

            // Find delivery that contains the searched invoice
            const matchingDelivery = deliveries.find(delivery =>
                delivery.invoiceNum.some(invoice => invoice.toString().includes(searchedInvoice)) // Convert to string to prevent type mismatches
            );

            if (matchingDelivery) {
                console.log("Found matching delivery:", matchingDelivery);
                setFilteredDeliveryId(matchingDelivery.id);
                calculateFilteredDeliveryRoute(matchingDelivery); // Call the function to calculate the route
            } else {
                console.warn("No delivery found for invoice:", searchedInvoice);
                setFilteredDeliveryId(null);
                setDeliveryRoutes({});
                setRouteInfos([]);
                alert('No delivery found with this invoice number');
            }
        };

        window.addEventListener('invoiceSearch', handleInvoiceSearch as EventListener);

        return () => {
            window.removeEventListener('invoiceSearch', handleInvoiceSearch as EventListener);
        };
    }, [deliveries]);

    const calculateFilteredDeliveryRoute = async (delivery: Delivery) => {
        setIsLoading(true);
        try {
            const deliveryRoutesMap: { [key: number]: [number, number][] } = {};
            const routeInformation: RouteInfo[] = [];

            // Construct coordinates string for OSRM trip endpoint
            let coordinates = `${delivery.lng},${delivery.lat}`;
            const reportsForDelivery = reports.filter(report => {
                return delivery.invoiceNum.some(invoiceNum =>
                    report.invoiceNum.toString().includes(invoiceNum.toString())
                );
            });

            if (reportsForDelivery.length === 0) {
                alert('No delivery points found for this invoice.');
                setIsLoading(false);
                return;
            }

            reportsForDelivery.forEach(report => {
                coordinates += `;${report.lng},${report.lat}`;
            });

            const response = await axios.get(
                `https://router.project-osrm.org/trip/v1/driving/${coordinates}?roundtrip=true&source=first`
            );

            if (response.data.trips && response.data.trips.length > 0) {
                deliveryRoutesMap[delivery.id] = decodePolyline(response.data.trips[0].geometry);
                routeInformation.push({
                    deliveryId: delivery.id,
                    shopName: delivery.shopName[0],
                    totalTime: Math.round(response.data.trips[0].duration / 60),
                    reports: reportsForDelivery.map(r => ({
                        description: r.description,
                        distance: calculateDistance(delivery.lat, delivery.lng, r.lat, r.lng)
                    }))
                });

                setDeliveryRoutes(deliveryRoutesMap);
                setRouteInfos(routeInformation);
                setIsInfoMinimized(false);
            } else {
                alert('Could not calculate route for this invoice.');
            }
        } catch (error) {
            console.error('Failed to calculate delivery route:', error);
            alert('Failed to calculate delivery route. Please try again.');
        } finally {
            setIsLoading(false);
        }
    };

    // Handle user location
    const getDeliveryGroup = (deliveryId: number): number => {
        // Return 0-3 based on your existing grouping logic
        return (deliveryId - 1) % 4;
    };

    // Helper function to determine report group
    const getReportGroup = (township: string): number => {
        if (location_gp1.includes(township)) return 0;
        if (location_gp2.includes(township)) return 1;
        if (location_gp3.includes(township)) return 2;
        if (location_gp4.includes(township)) return 3;
        return 0; // Default group
    };

    // Fetch initial data
    useEffect(() => {
        api.getShelters().then((res) => setShelters(res.data));
        api.getReports().then((res) => setReports(res.data));
        api.getDeliveries().then((res) => setDeliveries(res.data));
    }, []);



    // Modify calculateOptimizedRoute to show driver card
    const calculateOptimizedRoute = async () => {
        if (!userLocation) {
            alert('Please enable location services first.');
            return;
        }

        const driverDelivery = deliveries.find(d => d.driverName === selectedDriverName);

        if (!selectedDriverName || !driverDelivery) {
            alert('Please select a driver first.');
            return;
        }

        setIsLoading(true);
        try {
            // Filter reports based on the driver's delivery townships
            const driverReports = reports.filter(report => {
                const deliveryGroup = getDeliveryGroup(driverDelivery.id);
                const reportGroup = getReportGroup(report.township);
                return deliveryGroup === reportGroup;
            });

            if (driverReports.length === 0) {
                alert('No delivery points found for this driver.');
                setIsLoading(false);
                return;
            }

            // Start from driver's current location (userLocation)
            let coordinates = `${userLocation[1]},${userLocation[0]}`;
            driverReports.forEach(report => {
                coordinates += `;${report.lng},${report.lat}`;
            });

            // Get optimized trip order
            const tripResponse = await axios.get(
                `https://router.project-osrm.org/trip/v1/driving/${coordinates}?roundtrip=false&source=first`
            );

            const { trips, waypoints } = tripResponse.data;

            if (trips && trips.length > 0) {
                const sortedWaypoints = waypoints.sort((a: any, b: any) => a.waypoint_index - b.waypoint_index);
                setOptimizedWaypoints(sortedWaypoints);

                const segments: Array<[number, number][]> = [];
                const allPositions: [number, number][] = [userLocation];

                for (let i = 0; i < sortedWaypoints.length - 1; i++) {
                    const start = sortedWaypoints[i];
                    const end = sortedWaypoints[i + 1];

                    const routeResponse = await axios.get(
                        `https://router.project-osrm.org/route/v1/driving/${start.location[0]},${start.location[1]};${end.location[0]},${end.location[1]}?overview=full`
                    );

                    if (routeResponse.data.routes[0]) {
                        const decodedSegment = decodePolyline(routeResponse.data.routes[0].geometry);
                        segments.push(decodedSegment);
                        allPositions.push(...decodedSegment);
                    }
                }

                setRouteSegments(segments);
                setTotalTravelTime(Math.round(trips[0].duration / 60));

                // Store all positions for animation
                setAnimationRoute(allPositions);

                // Create waypoint order message
                const waypointOrder = sortedWaypoints.map((wp: any, index: number) => {
                    if (index === 0) return "Driver Start";
                    const report = driverReports[index - 1];
                    return `Stop ${index}: ${report.description.substring(0, 20)}...`;
                });

                alert(`Optimized Route Created for ${selectedDriverName}!\n\nTotal travel time: ${Math.round(trips[0].duration / 60)} minutes\n\nRoute order:\n${waypointOrder.join('\n')}`);

                setShowDriverCard(true); // Show driver card after successful optimization
            }
        } catch (error) {
            console.error('Failed to calculate optimized route:', error);
            alert('Failed to calculate optimized route. Please try again.');
        }
        setIsLoading(false);
    };


    // Calculate distance between two points using Haversine formula
    const calculateDistance = (
        lat1: number,
        lon1: number,
        lat2: number,
        lon2: number
    ): number => {
        const R = 6371; // Earth's radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    };

    const calculateDeliveryRoutes = async () => {
        if (filteredDeliveryId) {
            const delivery = deliveries.find(d => d.id === filteredDeliveryId);
            if (delivery) {
                calculateFilteredDeliveryRoute(delivery);
            }
        } else {
            const startTime = performance.now();
            setIsLoading(true);
            try {
                let availableReports = [...reports];
                const routeInformation: RouteInfo[] = [];
                const clusters: Array<Report[]> = [];
                const overallProximityData = { min: Infinity, max: 0, avg: 0 };
                let overallTotalDistances = 0;
                let overallDistanceCount = 0;

                // Track per-route proximity data
                const routeProximityData: { [key: number]: { min: number; max: number; avg: number; total: number; count: number } } = {};

                // Initialize clusters
                const numberOfGroups = 4;
                for (let i = 0; i < numberOfGroups; i++) {
                    clusters.push([]);
                }

                // First pass: Group reports by township and calculate proximities
                availableReports.forEach(report => {
                    let assignedToCluster = false;

                    // Calculate overall distances
                    availableReports.forEach(otherReport => {
                        if (report.id !== otherReport.id) {
                            const distance = calculateDistance(
                                report.lat, report.lng,
                                otherReport.lat, otherReport.lng
                            );
                            overallProximityData.min = Math.min(overallProximityData.min, distance);
                            overallProximityData.max = Math.max(overallProximityData.max, distance);
                            overallTotalDistances += distance;
                            overallDistanceCount++;
                        }
                    });

                    // Township grouping logic
                    if (location_gp1.includes(report.township)) {
                        clusters[0].push(report);
                        assignedToCluster = true;
                    } else if (location_gp2.includes(report.township)) {
                        clusters[1].push(report);
                        assignedToCluster = true;
                    } else if (location_gp3.includes(report.township)) {
                        clusters[2].push(report);
                        assignedToCluster = true;
                    } else if (location_gp4.includes(report.township)) {
                        clusters[3].push(report);
                        assignedToCluster = true;
                    }

                    // Fallback to nearest delivery if no township match
                    if (!assignedToCluster) {
                        let shortestDistance = Infinity;
                        let nearestClusterIndex = 0;

                        deliveries.forEach((delivery, index) => {
                            if (index >= numberOfGroups) return;
                            const distance = calculateDistance(
                                delivery.lat,
                                delivery.lng,
                                report.lat,
                                report.lng
                            );
                            if (distance < shortestDistance) {
                                shortestDistance = distance;
                                nearestClusterIndex = index;
                            }
                        });

                        clusters[nearestClusterIndex].push(report);
                    }
                });

                // Calculate per-route proximities after clusters are formed
                clusters.forEach((cluster, index) => {
                    if (cluster.length > 0 && index < deliveries.length) {
                        routeProximityData[deliveries[index].id] = {
                            min: Infinity,
                            max: 0,
                            avg: 0,
                            total: 0,
                            count: 0
                        };

                        cluster.forEach((report, i) => {
                            cluster.forEach((otherReport, j) => {
                                if (i !== j) {
                                    const distance = calculateDistance(
                                        report.lat, report.lng,
                                        otherReport.lat, otherReport.lng
                                    );
                                    const routeData = routeProximityData[deliveries[index].id];
                                    routeData.min = Math.min(routeData.min, distance);
                                    routeData.max = Math.max(routeData.max, distance);
                                    routeData.total += distance;
                                    routeData.count++;
                                }
                            });
                        });

                        // Calculate average for this route
                        const routeData = routeProximityData[deliveries[index].id];
                        if (routeData.count > 0) {
                            routeData.avg = routeData.total / routeData.count;
                        }
                    }
                });

                // Calculate routes for each cluster in parallel
                const routePromises = clusters.map(async (cluster, index) => {
                    if (!cluster.length || index >= deliveries.length) return null;
                    const delivery = deliveries[index];

                    let coordinates = `${delivery.lng},${delivery.lat}`;
                    cluster.forEach(report => {
                        coordinates += `;${report.lng},${report.lat}`;
                    });

                    try {
                        const response = await axios.get(
                            `https://router.project-osrm.org/trip/v1/driving/${coordinates}?roundtrip=true&source=first`
                        );

                        if (response.data.trips && response.data.trips.length > 0) {
                            return {
                                deliveryId: delivery.id,
                                route: decodePolyline(response.data.trips[0].geometry),
                                info: {
                                    deliveryId: delivery.id,
                                    shopName: delivery.shopName[0],
                                    totalTime: Math.round(response.data.trips[0].duration / 60),
                                    reports: cluster.map(r => ({
                                        description: r.description,
                                        distance: calculateDistance(delivery.lat, delivery.lng, r.lat, r.lng)
                                    }))
                                }
                            };
                        }
                    } catch (error) {
                        console.error(`Failed to calculate route for delivery ${delivery.id}:`, error);
                        return null;
                    }
                });

                // Wait for all route calculations to complete
                const results = await Promise.all(routePromises);
                const deliveryRoutesMap: { [key: number]: [number, number][] } = {};

                results.forEach(result => {
                    if (result) {
                        deliveryRoutesMap[result.deliveryId] = result.route;
                        routeInformation.push(result.info);
                    }
                });

                setDeliveryRoutes(deliveryRoutesMap);
                setRouteInfos(routeInformation);
                setIsInfoMinimized(false);

                const endTime = performance.now();
                overallProximityData.avg = overallTotalDistances / overallDistanceCount;

                // Format route-specific proximity stats
                const routeProximityStats: { [key: number]: string } = {};
                Object.entries(routeProximityData).forEach(([deliveryId, data]) => {
                    routeProximityStats[Number(deliveryId)] =
                        `Min: ${data.min.toFixed(2)}km, Max: ${data.max.toFixed(2)}km, Avg: ${data.avg.toFixed(2)}km`;
                });

                setAlgorithmStats({
                    timeComplexity: `O(n²) - where n is number of delivery locations`,
                    executionTime: Math.round(endTime - startTime),
                    overallProximityStats: `Min: ${overallProximityData.min.toFixed(2)}km, Max: ${overallProximityData.max.toFixed(2)}km, Avg: ${overallProximityData.avg.toFixed(2)}km`,
                    routeProximityStats
                });

            } catch (error) {
                console.error('Failed to calculate delivery routes:', error);
                alert('Failed to calculate delivery routes. Please try again.');
            }
            setIsLoading(false);
        }
    };

    // Modified user location handling
    useEffect(() => {
        // Try to get user location when component mounts
        handleGetUserLocation();
    }, []); // Run once on component mount

    // Updated location handler
    const handleGetUserLocation = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    const newPosition: [number, number] = [latitude, longitude];
                    setUserLocation(newPosition);
                    setPosition(newPosition);
                    // Add a marker for user's location
                    const marker = L.marker(newPosition, {
                        icon: DefaultIcon,
                    }).bindPopup('You are here');
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    alert(`Location error: ${error.message}`);
                },
                {
                    enableHighAccuracy: true, // Request high accuracy
                    timeout: 5000, // Time to wait for location
                    maximumAge: 0 // Don't use cached position
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    };


    // Calculate travel time
    const calculateTravelTime = async (to: [number, number]) => {
        if (!userLocation) {
            alert('Please enable location services first.');
            return;
        }
        try {
            const response = await axios.get(
                `https://router.project-osrm.org/route/v1/driving/${userLocation[1]},${userLocation[0]};${to[1]},${to[0]}?overview=full`
            );

            // Get the decoded polyline
            const geometry = response.data.routes[0].geometry;
            const decodedPolyline = decodePolyline(geometry);

            // Set the route and travel time
            setRoutePolyline(decodedPolyline);
            setTravelTime(Math.round(response.data.routes[0].duration / 60));
        } catch (error) {
            alert('Failed to get travel time.');
            setRoutePolyline([]);
            setTravelTime(null);
        }
    };
    // Updated decodePolyline function
    const decodePolyline = (encoded: string): [number, number][] => {
        let index = 0, lat = 0, lng = 0;
        const coordinates: [number, number][] = [];
        while (index < encoded.length) {
            let shift = 0;
            let result = 0;
            let b: number;
            // Decode first number (this was meant for the "longitude" value according to OSRM)
            do {
                b = encoded.charCodeAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            const deltaLat = (result & 1) ? ~(result >> 1) : (result >> 1);
            lat += deltaLat;

            // Decode second number (this was meant for the "latitude" value according to OSRM)
            shift = 0;
            result = 0;
            do {
                b = encoded.charCodeAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            const deltaLng = (result & 1) ? ~(result >> 1) : (result >> 1);
            lng += deltaLng;

            // IMPORTANT: OSRM returns coordinates as [longitude, latitude].
            // Leaflet requires them in [latitude, longitude] order.
            // Swap the order here.
            coordinates.push([lat * 1e-5, lng * 1e-5]);
        }
        return coordinates;
    };



    // Handle map clicks
    function MapClickHandler() {
        useMapEvents({
            click: (e) => {
                setSelectedPosition([e.latlng.lat, e.latlng.lng]);
                setShowForm(true);
                setFormType(null);
            },
        });
        return null;
    }
    return (
        <>
            <div className="relative w-full h-[calc(100vh-12rem)]">
                <MapContainer
                    center={position}
                    zoom={13}
                    className="w-full h-full"
                    style={{ minHeight: "600px" }}
                >
                    {/* Control Container with all buttons */}
                    <div className="absolute top-4 left-4 z-[9999] flex gap-2 flex-wrap leaflet-control">
                        <button
                            onClick={handleGetUserLocation}
                            className="px-4 py-2 bg-white text-gray-700 rounded-md shadow-md hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2 border border-gray-200"
                        >
                            📍 Get Driver Location
                        </button>

                        {userLocation && (
                            <>

                                <button
                                    onClick={calculateDeliveryRoutes}
                                    className="px-4 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600 transition-colors duration-200 flex items-center gap-2"
                                    disabled={isLoading}
                                >
                                    {isLoading ? '⏳ Processing...' : '🚚 Show Delivery Routes'}
                                </button>
                            </>
                        )}
                    </div>

                    <TileLayer
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    />
                    <MapClickHandler />

                    {/* Add Driver Location Marker */}
                    {userLocation && (
                        <Marker position={userLocation} icon={GreenIcon}>
                            <Popup>
                                <strong>Driver Location</strong>
                            </Popup>
                        </Marker>
                    )}

                    {/* Optimized Button click show route animation */}

                    {/* Route segments with different colors */}
                    {routeSegments.map((segment, index) => (
                        <Polyline
                            key={index}
                            positions={segment}
                            color="#4CAF50"
                            weight={4}
                            opacity={0.8}
                        >
                            <Popup>
                                Segment {index + 1}
                            </Popup>
                        </Polyline>
                    ))}

                    {/* Numbered lines for optimized waypoints */}

                    {optimizedWaypoints.length > 0 && (
                        <>
                            {optimizedWaypoints.map((waypoint: any, index: number) => {
                                // Skip the first waypoint (driver's location) as it does not need a marker
                                if (index > 0) {
                                    const startPoint = optimizedWaypoints[index - 1].location; // previous waypoint
                                    const endPoint = waypoint.location; // current waypoint

                                    // Add a Polyline for the segment between consecutive waypoints
                                    return (
                                        <Polyline
                                            key={`optimized-segment-${index}`}
                                            positions={[
                                                [startPoint[1], startPoint[0]], // Convert to [lat, lng]
                                                [endPoint[1], endPoint[0]], // Convert to [lat, lng]
                                            ]}
                                            color="blue"
                                            weight={4}
                                            opacity={0.8}
                                        >
                                            <Popup>
                                                <strong>Route Segment {index}</strong>
                                                <br />
                                                From: {reports[index - 1]?.description || 'Driver Location'}
                                                <br />
                                                To: {reports[index]?.description}
                                            </Popup>
                                        </Polyline>
                                    );
                                }
                                return null;
                            })}
                        </>
                    )}

                    {/* Use the RouteAnimation component for animating the driver */}
                    {animationRoute.length > 0 && (
                        <RouteAnimation key={animationRoute.length} route={animationRoute} />
                    )}

                    {/* Update shelter markers to show travel time */}
                    {shelters.map((shelter) => (
                        <Marker key={shelter.id} position={[shelter.lat, shelter.lng]} icon={DefaultIcon}>
                            <Popup>
                                <h4>{shelter.name}</h4>
                                <p>Contact: {shelter.contact}</p>
                                <button onClick={() => calculateTravelTime([shelter.lat, shelter.lng])}>
                                    🚗 Get Directions
                                </button>
                                {travelTime && userLocation &&
                                    shelter.lat === routePolyline[routePolyline.length - 1]?.[0] &&
                                    shelter.lng === routePolyline[routePolyline.length - 1]?.[1] && (
                                        <p style={{ marginTop: '10px', color: '#0066cc' }}>
                                            Estimated travel time: {travelTime} minutes
                                        </p>
                                    )}
                            </Popup>
                        </Marker>
                    ))}

                    {/* Add Delivery Markers */}
                    {deliveries.map((delivery) => (
                        <Marker
                            key={`delivery-${delivery.id}`}
                            position={[delivery.lat, delivery.lng]}
                            icon={PurpleIcon}
                        >
                            <Popup>
                                <div>
                                    <h4>Delivery #{delivery.id}</h4>
                                    <p><strong>Driver:</strong> {delivery.driverName}</p>
                                    <p><strong>Contact:</strong> {delivery.driverContact}</p>
                                    <p><strong>Status:</strong> {delivery.status}</p>
                                    <p><strong>Deliveries:</strong></p>
                                    <ul style={{ margin: '5px 0', paddingLeft: '20px' }}>
                                        {delivery.invoiceNum.map((invoice, index) => (
                                            <li key={invoice}>
                                                Invoice #{invoice} - {delivery.shopName[index]}
                                            </li>
                                        ))}
                                    </ul>
                                    {userLocation && (
                                        <button onClick={() => calculateTravelTime([delivery.lat, delivery.lng])}>
                                            🚗 Get Directions
                                        </button>
                                    )}
                                </div>
                            </Popup>
                        </Marker>
                    ))}

                    {/* Add delivery points with different colors */}
                    {Object.entries(deliveryRoutes).map(([deliveryId, route]) => (
                        <Polyline
                            key={`route-${deliveryId}`}
                            positions={route}
                            color={`hsl(${(parseInt(deliveryId) * 137) % 360}, 70%, 50%)`}
                            weight={4}
                            opacity={0.8}
                        >
                            <Popup>
                                Delivery Route {deliveryId}
                                <br />
                                {deliveries.find(d => d.id === parseInt(deliveryId))?.shopName[0]}
                            </Popup>
                        </Polyline>
                    ))}

                    {reports.map((report) => (
                        <Marker key={report.id} position={[report.lat, report.lng]} icon={RedIcon}>
                            <Popup>{report.description}</Popup>
                        </Marker>
                    ))}

                    {/* Add polylines connecting deliveries to their reports */}
                    {routeInfos.map((info) => (
                        <React.Fragment key={`delivery-lines-${info.deliveryId}`}>
                            {info.reports.map((report, index) => {
                                const delivery = deliveries.find(d => d.id === info.deliveryId);
                                if (!delivery) return null;

                                return (
                                    <Polyline
                                        key={`delivery-${info.deliveryId}-report-${index}`}
                                        positions={[
                                            [delivery.lat, delivery.lng], // Delivery location
                                            [reports.find(r => r.description === report.description)?.lat || 0,
                                                reports.find(r => r.description === report.description)?.lng || 0] // Report location
                                        ]}
                                        color={`hsl(${(info.deliveryId * 137) % 360}, 70%, 50%)`}
                                        weight={5}
                                        opacity={1}

                                    >
                                        <Popup>
                                            <strong>Delivery Route {info.deliveryId}</strong>
                                            <br />
                                            From: {info.shopName}
                                            <br />
                                            To: {report.description}
                                            <br />
                                            Distance: {report.distance.toFixed(2)} km
                                        </Popup>
                                    </Polyline>
                                );
                            })}
                        </React.Fragment>
                    ))}

                    {/* Add route polylines */}
                    {routeSegments.map((segment, index) => (
                        <Polyline
                            key={index}
                            positions={segment}
                            color="#4CAF50"
                            weight={4}
                        />
                    ))}

                </MapContainer>
            </div>
            <DeliveryInfo routeInfos={routeInfos} />
        </>
    );
};

// Improved RouteAnimation component with explicit zIndexOffset and fallback icon
const RouteAnimation: React.FC<{ route: [number, number][] }> = ({ route }) => {
    const map = useMap();

    useEffect(() => {
        if (!route || route.length === 0) return;

        console.log("RouteAnimation started, route length:", route.length);

        // Create an icon – if carIcon fails to load, fallback to a known online image.
        const animationIcon = L.icon({
            iconUrl: carIcon || "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png",
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        // Add zIndexOffset to ensure the marker is visible on top of other layers.
        const marker = L.marker(route[0], {
            icon: animationIcon,
            zIndexOffset: 1000
        }).addTo(map);

        let startTime: number | null = null;
        let index = 0;
        const segmentDuration = 200; // duration (in ms) per segment for visible movement

        const animateMarker = (timestamp: number) => {
            if (startTime === null) {
                startTime = timestamp;
            }
            const elapsed = timestamp - startTime;
            if (elapsed >= segmentDuration) {
                index++;
                if (index >= route.length) {
                    console.log("Animation complete");
                    return; // End the animation when the route is complete.
                }
                marker.setLatLng(route[index]);
                console.log("Marker moved to:", route[index]);
                startTime = timestamp;
            }
            requestAnimationFrame(animateMarker);
        };

        requestAnimationFrame(animateMarker);

        return () => {
            console.log("Cleaning up marker");
            map.removeLayer(marker);
        };
    }, [map, route]);

    return null;
};

export default MapComponent;
