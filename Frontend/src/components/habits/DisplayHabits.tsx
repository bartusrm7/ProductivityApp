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
			<div className='d-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-3'>Description</div>
				<div className='col-2'>Date</div>
				<div className='col-2 text-center'>Actions</div>
			</div>
			{habitsData.map((habit, index) => (
				<div className='d-flex align-items-center border-bottom py-2' key={index}>
					<div className='col-1'>{habit.id}</div>
					<div className='col-3'>{habit.name}</div>
					<div className='col-3'>{habit.description}</div>
					<div className='col-2'>{habit.created_at}</div>
					<div className='col-2 text-center'></div>
				</div>
			))}
		</div>
	);
}
