import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserHabitDetailsData } from "../../types/habits";

export default function DisplayStartedHabit() {
	const [habitsDetails, setHabitsDetails] = useState<UserHabitDetailsData[]>([]);

	async function getStartedHabits() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-started-habits?status=started", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsDetails(data.data);
		}
	}

	useEffect(() => {
		getStartedHabits();
	}, []);

	return (
		<div>
			<div className='d-flex align-items-center'>
				<IoIosArrowUp size={24} />
				<h4 className='ms-2 mb-0'>Done</h4>
			</div>
			<div className='d-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-3'>Description</div>
				<div className='col-2'>Date</div>
				<div className='col-2 text-center'>Actions</div>
			</div>
			{habitsDetails.map((habit, index) => (
				<div className='d-flex align-items-center border-bottom py-2' key={index}>
					<div className='col-1'>{habit.id}</div>
					<div className='col-3'>{habit.streakDays}</div>
					<div className='col-3'>{habit.checkCurrentDay}</div>
					<div className='col-2'>{habit.amountDaysDone}</div>
					<div className='col-2 text-center'></div>
				</div>
			))}
		</div>
	);
}
