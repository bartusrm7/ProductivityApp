import { useEffect, useState } from "react";

export default function HabitStreaks() {
	const [currentHabitStreaks, setCurrentHabitStreaks] = useState<number>();
	const [bestHabitStreaks, setBestHabitStreaks] = useState<number>();

	async function getCurrentHabitStreaks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-current-habits-streak", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setCurrentHabitStreaks(data.data.streak_days);
		}
	}

	async function getBestHabitStreaks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-best-habits-streak", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setBestHabitStreaks(data.data.streak_days);
		}
	}

	useEffect(() => {
		getCurrentHabitStreaks();
		getBestHabitStreaks();
	}, []);

	return (
		<div className='my-2'>
			<div>
				🔥 Current best streak: <span className='fw-bold'>{currentHabitStreaks || 0}</span>
			</div>
			<div>
				🏆 Best streak overall: <span className='fw-bold'>{bestHabitStreaks || 0}</span>
			</div>
		</div>
	);
}
