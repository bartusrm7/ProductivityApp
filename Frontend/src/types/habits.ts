export interface UserHabitData {
	id: number;
	name: string;
	description: string;
	created_at: string;
}

export interface UserHabitDetailsData {
	id: number;
	streakDays: number;
	checkCurrentDay: string;
	amountDaysDone: number;
}
