import { BrowserRouter, Routes, Route } from "react-router-dom";
import SignUp from "./components/auth/SignUp";
import SignIn from "./components/auth/SignIn";
import Dashboard from "./components/dashboard/Dashboard";
import ProtectedRoute from "./components/ProtectedRoute";
import Tasks from "./components/tasks/Tasks";
import Habits from "./components/habits/Habits";
import Notes from "./components/notes/Notes";

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
				<Route
					path='/habits'
					element={
						<ProtectedRoute>
							<Habits />
						</ProtectedRoute>
					}
				/>
				<Route
					path='/notes'
					element={
						<ProtectedRoute>
							<Notes />
						</ProtectedRoute>
					}
				/>
			</Routes>
		</BrowserRouter>
	);
}
