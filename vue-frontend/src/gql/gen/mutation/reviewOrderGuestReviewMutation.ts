import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, ReviewOrderGuestReviewResult} from "../schema"

import {MutationReviewOrderGuestReviewArgs} from "../schema"

export type MutationReviewOrderGuestReviewVars = MutationReviewOrderGuestReviewArgs

export function reviewOrderGuestReviewMutation(mutation: MutationGenqlSelection['review_order_guest_review'] | null) {            
    return {
      build: (variables: MutationReviewOrderGuestReviewVars) => genqlBuilder.build(mutation ? {review_order_guest_review: mutation} : {__scalar: true}, {review_order_guest_review: variables}),  
      mutate: (variables: MutationReviewOrderGuestReviewVars) => ({} as Promise<ReviewOrderGuestReviewResult>),
      _variables: {} as MutationReviewOrderGuestReviewVars,
      _result: {} as ReviewOrderGuestReviewResult,
    }
}

export type MutationReviewOrderGuestReview = {
  builder: (variables: MutationReviewOrderGuestReviewVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['review_order_guest_review'],
  variables: MutationReviewOrderGuestReviewVars,
  result: ReviewOrderGuestReviewResult
}

export default reviewOrderGuestReviewMutation