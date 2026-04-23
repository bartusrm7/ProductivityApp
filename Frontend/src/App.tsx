import { BrowserRouter, Routes, Route } from "react-router-dom";
import SignUp from "./components/auth/SignUp";
import SignIn from "./components/auth/SignIn";
import Dashboard from "./components/dashboard/Dashboard";
import ProtectedRoute from "./components/ProtectedRoute";
import Tasks from "./components/tasks/Tasks";

export default function App() {
	return (
		<BrowserRouter>
			<Routes>
				<Route path='/sign-in' element={<SignIn />} />
				<Route path='/sign-up' element={<SignUp />} />
				<Route
					path='/dashboard'
					element={
						<ProtectedRoute>
							<Dashboard />
						</ProtectedRoute>
					}
				/>
				<Route
					path='/tasks'
					element={
						<ProtectedRoute>
							<Tasks />
						</ProtectedRoute>
					}
				/>
			</Routes>
		</BrowserRouter>
	);
}
