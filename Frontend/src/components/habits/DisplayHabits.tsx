import { useEffect, useState } from "react";
import type { UserHabitData } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import { IoIosArrowUp } from "react-icons/io";
import StartHabit from "./StartHabit";

export default function DisplayHabits() {
	const [habitsData, setHabitsData] = useState<UserHabitData[]>([]);
	const [refresh, setRefresh] = useState<number>(0);

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
			setRefresh(prevState => prevState + 1);
		}
	}

	useEffect(() => {
		getAllHabits();
	}, [refresh]);

	return (
		<div className='display-habits'>
			<div className='d-flex align-items-center border-bottom pb-1'>
				<IoIosArrowUp size={24} />
				<h4 className='ms-2 mb-0'>Done</h4>
			</div>
			<div className='header-custom-table-names d-none d-md-flex border-bottom'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-3'>Description</div>
				<div className='col-2'>Date</div>
				<div className='col-3 text-center'>Actions</div>
			</div>
			{habitsData.map((habit, index) => (
				<div className='display-habits__main-container custom-table-row d-flex flex-wrap align-items-center border-bottom' key={index}>
					<div className='display-habits__id col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
					<div className='display-habits__name display-habit__name-row col-12 col-md-3'>{habit.name}</div>
					<div className='display-habits__description col-12 col-md-3'>{habit.description}</div>
					<div className='display-habits__created_at col-8 col-md-2'>{habit.created_at}</div>
					<div className='display-habits__buttons-container d-flex justify-content-center col-4 col-md-3'>
						<StartHabit habitId={habit.id} />
						<EditHabit habitProp={habit} />
						<DeleteHabit habitId={habit.id} />
					</div>
				</div>
			))}
		</div>
	);
}
