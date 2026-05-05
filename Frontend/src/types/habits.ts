export interface UserHabitData {
	id: number;
	name: string;
	description: string;
	created_at: string;
}

export interface UserHabitDetailsData {
	habit_id: number;
	streakDays: number;
	checkCurrentDay: string;
	amountDaysDone: number;
}

export interface UserHabitDetailsDataJoined {
	id: number;
	name: string;
	description: string;
	created_at: string;
	streak_days: number;
	check_current_day: string;
	amount_days_done: number;
	habit_id: number;
}
