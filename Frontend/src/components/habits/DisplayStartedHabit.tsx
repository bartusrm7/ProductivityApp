import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserHabitDetailsDataJoined } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import SetHabitAsDone from "./SetHabitAsDone";

export default function DisplayStartedHabit() {
	const [habitsDetails, setHabitsDetails] = useState<UserHabitDetailsDataJoined[]>([]);

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
				<h4 className='ms-2 mb-0'>Started</h4>
			</div>
			<div className='d-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-2'>Description</div>
				<div className='col-1'>Streak</div>
				<div className='col-1'>Days Done</div>
				<div className='col-2'>Date</div>
				<div className='col-2 text-center'>Actions</div>
			</div>
			{habitsDetails.map((habit, index) => (
				<div className='d-flex border-bottom py-2' key={index}>
					<div className='col-1'>{habit.id}</div>
					<div className='col-3'>{habit.name}</div>
					<div className='col-2'>{habit.description}</div>
					<div className='col-1'>{habit.streak_days}</div>
					<div className='col-1'>{habit.amount_days_done}</div>
					<div className='col-2'>{habit.created_at}</div>
					<div className='col-2 text-center'>
						<SetHabitAsDone habitId={habit.id} streakDays={habit.streak_days} amountDaysDone={habit.amount_days_done} />
						<EditHabit habitProp={habit} />
						<DeleteHabit habitId={habit.id} />
					</div>
				</div>
			))}
		</div>
	);
}
