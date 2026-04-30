import { useEffect, useState } from "react";
import type { UserHabitData } from "../../types/habits";

export default function DisplayHabits() {
	const [habitsData, setHabitsData] = useState<UserHabitData[]>([]);

	async function getAllHabits() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-habits", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsData(data.data);
		}
	}

	useEffect(() => {
		getAllHabits();
	}, []);

	return (
		<div>
			{habitsData.map((habit, index) => (
				<div key={index}>{habit.name}</div>
			))}
		</div>
	);
}
